<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Resources\Issues\Pages;

use App\Filament\Resources\Projects\Resources\Issues\IssueResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ViewIssue extends ViewRecord
{
    protected static string $resource = IssueResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Issue Information')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title')
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),

                        TextEntry::make('priority')
                            ->label('Priority')
                            ->badge(),

                        TextEntry::make('due_date')
                            ->label('Due Date')
                            ->date(),

                        TextEntry::make('project.name')
                            ->label('Project'),

                        TextEntry::make('users.name')
                            ->label('Assigned Users')
                            ->listWithLineBreaks()
                            ->limitList(3)
                            ->expandableLimitedList(),

                        TextEntry::make('tags')
                            ->label('Tags')
                            ->formatStateUsing(function ($record) {
                                if (! $record->tags || $record->tags->isEmpty()) {
                                    return 'No tags assigned';
                                }

                                return $record->tags->map(function ($tag) {
                                    $style = '';
                                    $textColor = '#000000';

                                    if ($tag->color) {
                                        $style = "background-color: {$tag->color};";
                                        $textColor = self::getContrastColor($tag->color);
                                    } else {
                                        $style = 'background-color: #e5e7eb;'; // Default gray
                                    }

                                    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1 mb-1\" style=\"{$style} color: {$textColor};\">{$tag->name}</span>";
                                })->join('');
                            })
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Created')
                            ->since()
                            ->dateTimeTooltip(),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->since()
                            ->dateTimeTooltip(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Discussion')
                    ->description('Join the conversation about this issue')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        // Add comment action
                        \Filament\Schemas\Components\Actions::make([
                            Action::make('add_comment')
                                ->label('Add Comment')
                                ->icon('heroicon-o-plus-circle')
                                ->color('primary')
                                ->size('lg')
                                ->form([
                                    Textarea::make('body')
                                        ->label('Your Comment')
                                        ->placeholder('Share your thoughts, feedback, or questions about this issue...')
                                        ->rows(4)
                                        ->required()
                                        ->minLength(3)
                                        ->maxLength(1000)
                                        ->helperText('Markdown formatting is supported')
                                        ->extraInputAttributes([
                                            'style' => 'resize: vertical; min-height: 100px;',
                                        ]),
                                ])
                                ->action(function (array $data): void {
                                    $this->record->comments()->create([
                                        'body' => $data['body'],
                                    ]);

                                    Notification::make()
                                        ->title('Comment added successfully')
                                        ->body('Your comment has been posted and is now visible to all participants.')
                                        ->success()
                                        ->duration(5000)
                                        ->send();

                                    // Refresh the current component to show the new comment
                                    $this->dispatch('$refresh');
                                })
                                ->modalSubmitActionLabel('Post Comment')
                                ->modalCancelActionLabel('Cancel')
                                ->modalHeading('Add Your Comment')
                                ->modalDescription('Share your thoughts about this issue. Your comment will be visible to all team members.')
                                ->modalWidth('2xl'),
                        ])
                            ->alignment('left'),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->persistCollapsed(),
            ]);
    }

    /**
     * Calculate contrast color (black or white) based on background color
     */
    private static function getContrastColor(string $hexColor): string
    {
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');

        // Convert to RGB
        if (mb_strlen($hexColor) === 3) {
            $hexColor = $hexColor[0] . $hexColor[0] . $hexColor[1] . $hexColor[1] . $hexColor[2] . $hexColor[2];
        }

        if (mb_strlen($hexColor) !== 6) {
            return '#000000'; // Default to black for invalid colors
        }

        $r = hexdec(mb_substr($hexColor, 0, 2));
        $g = hexdec(mb_substr($hexColor, 2, 2));
        $b = hexdec(mb_substr($hexColor, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return black for light backgrounds, white for dark backgrounds
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }
}
