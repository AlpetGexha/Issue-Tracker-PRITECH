<?php

namespace App\Filament\Resources\Projects\Resources\Issues\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class IssuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                // TextColumn::make('description')
                // ->searchable()
                // ->wrap(),
                TextColumn::make('status')
                ->badge(),
                TextColumn::make('priority')
                ->badge(),
                TextColumn::make('users.name')
                    ->label('Assigned Users')
                    ->badge()
                    ->wrap()
                    ->separator(', ')
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->wrap()
                    ->badge()
                    ->separator(', ')
                    ->searchable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('assign_users')
                        ->label('Assign Users')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Select::make('users')
                                ->label('Users to Assign')
                                ->relationship('users', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->default(fn ($record) => $record->users->pluck('name')->toArray()),
                        ])
                        ->action(function ($record, array $data): void {
                            if (isset($data['users'])) {
                                $record->users()->sync($data['users']);
                            }
                        })
                        ->successNotificationTitle('Users assigned successfully'),

                    Action::make('assign_tags')
                        ->label('Assign Tags')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Select::make('tags')
                                ->label('Tags to Assign')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->default(fn ($record) => $record->tags->pluck('name')->toArray()),
                        ])
                        ->action(function ($record, array $data): void {
                            if (isset($data['tags'])) {
                                $record->tags()->sync($data['tags']);
                            }
                        })
                        ->successNotificationTitle('Tags assigned successfully'),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
