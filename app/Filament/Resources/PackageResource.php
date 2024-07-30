<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Package Information')
                            ->description('Information about the package of questions.')
                            ->icon('heroicon-m-folder')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull()
                                    ->rows(5),
                                Group::make([
                                    Forms\Components\TextInput::make('duration')
                                        ->required()
                                        ->numeric()
                                        ->label('Duration (Minutes)'),
                                    Forms\Components\Toggle::make('is_active')
                                        ->default(true)
                                        ->inline(false),
                                ])
                                    ->columns(2)
                            ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Package Question')
                            ->description('Set of questions in a package.')
                            ->icon('heroicon-m-question-mark-circle')
                            ->schema([
                                Repeater::make('packageQuestions')
                                    ->relationship()
                                    ->schema([
                                        Select::make('question_id')
                                            ->relationship('question', 'question')
                                            ->required()
                                            ->allowHtml()
                                            ->searchable()
                                            ->preload()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ])
                            ])
                    ])
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->lineClamp(2)
                    ->wrap(),
                Tables\Columns\TextColumn::make('package_questions_count')
                    ->label('Total Questions')
                    ->counts('packageQuestions'),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable()
                    ->label('Duration (Minutes)'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Is Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ActionGroup::make([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
                    ])
                        ->dropdown(false),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-s-bars-3')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
