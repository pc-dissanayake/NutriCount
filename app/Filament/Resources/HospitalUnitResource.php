<?php

namespace App\Filament\Resources;

use App\Enums\MedicalSpecialty;
use App\Filament\Resources\HospitalUnitResource\Pages;
use App\Filament\Resources\HospitalUnitResource\RelationManagers;
use App\Models\HospitalUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HospitalUnitResource extends Resource
{
    protected static ?string $model = HospitalUnit::class;

    protected static ?string $navigationIcon = 'healthicons-o-hospice';
    protected static ?string $navigationGroup = 'NHSL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                        ->columnspanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('Name')->required(),
                                Forms\Components\Toggle::make('active')->label('Active')->default(true),
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->options(MedicalSpecialty::options())->searchable()
                                    ->nullable(), Forms\Components\Select::make('specialty')
                                    ->label('Specialty')
                                    ->options(MedicalSpecialty::options())->searchable()
                                    ->nullable(),
                                Forms\Components\TextInput::make('alias')->label('Alias')->nullable(),

                                Forms\Components\TagsInput::make('tags')
                                    ->label('Tags')
                                    ->placeholder('Add tags...')
                                    ->separator(',')
                                    ->suggestions(['ward', 'clinic', 'icu', 'surgery'])
                                    ->nullable(),
                                Forms\Components\TextInput::make('order_id')
                                    ->label('Order ID')
                                    ->default(fn () => \App\Models\HospitalUnit::max('order_id') + 1)
                                    ->readOnly(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Other')
                            ->schema([
                                Forms\Components\Textarea::make('description')->label('Description')->nullable(),
                                Forms\Components\Select::make('contact')
                                    ->label('Contact User')
                                    ->relationship('contactUser', 'name')
                                    ->searchable()
                                    ->nullable(),
                                Forms\Components\TextInput::make('part_of')->label('Part Of (UUID)')->nullable(),
                                Forms\Components\TextInput::make('endpoint')->label('Endpoint')->nullable(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Qualification')
                            ->schema([
                                Forms\Components\TextInput::make('qualification_code')->label('Qualification Code')->nullable(),
                                Forms\Components\TextInput::make('qualification_identifier')->label('Qualification Identifier')->nullable(),
                                Forms\Components\DatePicker::make('qualification_period_start')->label('Qualification Period Start')->nullable(),
                                Forms\Components\DatePicker::make('qualification_period_end')->label('Qualification Period End')->nullable(),
                                Forms\Components\TextInput::make('qualification_issuer')->label('Qualification Issuer')->nullable(),
                            ]),
                        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->label('Order ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Type'),
                Tables\Columns\TextColumn::make('identifier')->label('Identifier'),
                Tables\Columns\TextColumn::make('alias')->label('Alias'),
                Tables\Columns\TextColumn::make('description')->label('Description'),
                Tables\Columns\TextColumn::make('tags')->label('Tags')->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state),
                Tables\Columns\ToggleColumn::make('active')->label('Active'),
            ])
            ->defaultSort('order_id')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListHospitalUnits::route('/'),
            'create' => Pages\CreateHospitalUnit::route('/create'),
            'edit' => Pages\EditHospitalUnit::route('/{record}/edit'),
            'view' => Pages\ViewHospitalUnit::route('/{record}'),
        ];
    }
}
