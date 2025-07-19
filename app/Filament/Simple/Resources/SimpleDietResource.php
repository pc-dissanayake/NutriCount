<?php

namespace App\Filament\Simple\Resources;

use App\Filament\Simple\Resources\SimpleDietResource\Pages;
use App\Filament\Simple\Resources\SimpleDietResource\RelationManagers;
use App\Models\SimpleDiet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class SimpleDietResource extends Resource
{
    protected static ?string $model = SimpleDiet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Diet';
    protected static ?string $label = "Diet Types";

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        $nextOrder = (SimpleDiet::max('list_order') ?? 0) + 1;
        return $form
            ->schema([
                Forms\Components\TextInput::make('DietName_en')
                    ->label('Diet Name (English)')
                    ->required()
                    ->columnSpanFull()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('translate')
                            ->label('Translate')
                            ->action(function (\Filament\Forms\Set $set, $state) {
                                // MyMemory API for Sinhala
                                $siResponse = Http::withoutVerifying()->get('https://api.mymemory.translated.net/get', [
                                    'q' => $state,
                                    'langpair' => 'en|si',
                                ]);
                                $si = $siResponse->json('responseData.translatedText') ?? '';
                                // MyMemory API for Tamil
                                $tmResponse = Http::withoutVerifying()->get('https://api.mymemory.translated.net/get', [
                                    'q' => $state,
                                    'langpair' => 'en|ta',
                                ]);
                                $tm = $tmResponse->json('responseData.translatedText') ?? '';
                                $set('DietName_si', $si);
                                $set('DietName_tm', $tm);
                            })
                    ),
                Forms\Components\TextInput::make('DietName_si')->label('Diet Name (Sinhala)')->nullable()->columnSpanFull(),
                Forms\Components\TextInput::make('DietName_tm')->label('Diet Name (Tamil)')->nullable()->columnSpanFull(),
                Forms\Components\Toggle::make('active')->label('Active')->default(true)->columnSpanFull(),
                Forms\Components\Section::make('Primary Amount')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('primary_amount_value')
                            ->label('Primary Amount Value')
                            ->numeric()
                            ->nullable()->columnSpan(2),
                        Forms\Components\Select::make('primary_amount_unit')
                            ->label('Primary Amount Unit')
                            ->required()
                            ->options([
                                // Mass units
                                'mcg' => 'mcg (microgram)',
                                'mg' => 'mg (milligram)',
                                'g' => 'g (gram)',
                                'kg' => 'kg (kilogram)',
                                'oz' => 'oz (ounce)',
                                'lb' => 'lb (pound)',
                                'st' => 'st (stone)',
                                'gr' => 'gr (grain)',
                                'ct' => 'ct (carat)',
                                // Fluid/volume units
                                'ml' => 'ml (milliliter)',
                                'l' => 'l (liter)',
                                'cl' => 'cl (centiliter)',
                                'dl' => 'dl (deciliter)',
                                'tsp' => 'tsp (teaspoon)',
                                'tbsp' => 'tbsp (tablespoon)',
                                'fl oz' => 'fl oz (fluid ounce)',
                                'cup' => 'cup',
                                'pt' => 'pt (pint)',
                                'qt' => 'qt (quart)',
                                'gal' => 'gal (gallon)',
                                'cc' => 'cc (cubic centimeter)',
                                'm3' => 'm³ (cubic meter)',
                                // Imperial/US units
                                'us_fl_oz' => 'US fl oz (US fluid ounce)',
                                'uk_fl_oz' => 'UK fl oz (UK fluid ounce)',
                                'us_cup' => 'US cup',
                                'uk_cup' => 'UK cup',
                                'us_pt' => 'US pint',
                                'uk_pt' => 'UK pint',
                                'us_qt' => 'US quart',
                                'uk_qt' => 'UK quart',
                                'us_gal' => 'US gallon',
                                'uk_gal' => 'UK gallon',
                                // Other common units
                                'piece' => 'piece',
                                'serving' => 'serving',
                                'slice' => 'slice',
                                'portion' => 'portion',
                                'drop' => 'drop',
                                'pinch' => 'pinch',
                                'dash' => 'dash',
                                'sprig' => 'sprig',
                                'handful' => 'handful',
                                'bunch' => 'bunch',
                                'stick' => 'stick',
                                'sheet' => 'sheet',
                                'package' => 'package',
                                'container' => 'container',
                                'can' => 'can',
                                'bottle' => 'bottle',
                                'jar' => 'jar',
                                'bag' => 'bag',
                                'box' => 'box',
                                'bar' => 'bar',
                                'packet' => 'packet',
                                'tube' => 'tube',
                                'unit' => 'unit',
                            ])
                            ->searchable()
                            ->nullable(),
                    ]),
                Forms\Components\TextInput::make('list_order')
                    ->label('List Order')
                    ->numeric()
                    ->nullable()
                    ->default($nextOrder)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('list_order')
            ->columns([
                Tables\Columns\TextColumn::make('DietName_en')->label('Diet Name (English)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_si')->label('Diet Name (Sinhala)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_tm')->label('Diet Name (Tamil)')->searchable(),
                Tables\Columns\ToggleColumn::make('active')->label('Active'),
                
                Tables\Columns\TextColumn::make('primary_amount_value')
                    ->label('Primary Amount Value')
                    ->sortable()
                    ->columnSpan(3),
                Tables\Columns\TextColumn::make('primary_amount_unit')
                    ->label('Primary Amount Unit')
                    ->sortable()
                    ->columnSpan(2),
                Tables\Columns\TextColumn::make('list_order')->label('List Order')->sortable(),
            ])
                        ->defaultSort('list_order')
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
            'index' => Pages\ListSimpleDiets::route('/'),
            'create' => Pages\CreateSimpleDiet::route('/create'),
            'edit' => Pages\EditSimpleDiet::route('/{record}/edit'),
            'view' => Pages\ViewSimpleDiet::route('/{record}'),
        ];
    }
}
