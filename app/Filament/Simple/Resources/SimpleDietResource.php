<?php

namespace App\Filament\Simple\Resources;

use App\Filament\Simple\Resources\SimpleDietResource\Pages;
use App\Filament\Simple\Resources\SimpleDietResource\RelationManagers;
use App\Models\SimpleDiet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SimpleDietResource extends Resource
{
    protected static ?string $model = SimpleDiet::class;

    protected static ?string $navigationIcon = 'fluentui-food-fish-20';
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
                Forms\Components\Select::make('category')
                    ->label('Food Category')
                    ->multiple()
                    ->options([
                        'Basic Food' => [
                            'grains_cereals' => 'General Grains & Cereals',
                            'rice' => 'Rice & Rice Products',
                            'wheat' => 'Wheat & Wheat Products',
                            'bread' => 'Bread & Bakery Products',
                            'pasta' => 'Pasta & Noodles',
                            'meat' => 'Meat & Poultry',
                            'seafood' => 'Fish & Seafood',
                            'eggs' => 'Eggs & Egg Products',
                            'legumes' => 'Legumes & Beans',
                            'nuts_seeds' => 'Nuts & Seeds',
                            'protein_alternatives' => 'Protein Alternatives',
                            'milk' => 'Milk & Milk Products',
                            'fruits' => 'General Fruits',
                            'vegetables' => 'General Vegetables',
                            'leafy_greens' => 'Leafy Greens',
                            'root_vegetables' => 'Root Vegetables',
                            'citrus_fruits' => 'Citrus Fruits',
                            'tropical_fruits' => 'Tropical Fruits',
                            'fats_oils' => 'General Fats & Oils',
                            'cooking_oils' => 'Cooking Oils',
                            'butter_margarine' => 'Butter & Margarine',
                            'snacks' => 'General Snacks',
                            'sweets_desserts' => 'Sweets & Desserts',
                            'chocolate' => 'Chocolate & Confectionery',



                        ],
                        'Beverages' => [
                            'beverages' => 'General Beverages',
                            'water' => 'Water',
                            'tea_coffee' => 'Tea & Coffee',
                            'juices' => 'Fruit & Vegetable Juices',
                            'soft_drinks' => 'Soft Drinks',
                        ],
                        
                        'Condiments & Seasonings' => [
                            'condiments' => 'Condiments & Sauces',
                            'spices_herbs' => 'Spices & Herbs',
                            'vinegar' => 'Vinegar & Acidic Condiments',
                        ],
                        'Special Diets' => [
                            'sports_nutrition' => 'Sports Nutrition',
                            'supplements' => 'Dietary Supplements',
                            'meal_replacements' => 'Meal Replacements',
                        ],
                        'Preparation Methods' => [
                            'raw' => 'Raw Foods',
                            'cooked' => 'Cooked Foods',
                            'steamed' => 'Steamed',
                            'fried' => 'Fried Foods',
                            'grilled' => 'Grilled Foods',
                            'baked' => 'Baked Foods',
                        ],
                        'Dietary Restrictions' => [
                            'vegetarian' => 'Vegetarian',
                            'vegan' => 'Vegan',
                            'gluten_free' => 'Gluten-Free',
                            'dairy_free' => 'Dairy-Free',
                            'low_sodium' => 'Low Sodium',
                            'low_fat' => 'Low Fat',
                            'low_sugar' => 'Low Sugar',
                            'keto_friendly' => 'Keto-Friendly',
                            'diabetic_friendly' => 'Diabetic-Friendly',
                        ],
                        'Food Processing & Storage' => [
                            'processed_foods' => 'Processed Foods',
                            'organic' => 'Organic',
                            'fast_food' => 'Fast Food',
                            'street_food' => 'Street Food',
                            'instant_foods' => 'Instant Foods',
                            'preserved_foods' => 'Preserved Foods',
                            'fermented_foods' => 'Fermented Foods',
                        ],
                    ])
                    ->searchable()
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Select one or more categories that best describe this diet type'),
                Forms\Components\Toggle::make('active')->label('Active')->default(true)->columnSpanFull(),
                Forms\Components\Section::make('Primary Amount')
                    ->columns(5)
                    ->schema([
                        Forms\Components\TextInput::make('primary_amount_value')
                            ->label('Primary Amount Value')
                            ->numeric()
                            ->nullable()->columnSpan(2),
                        Forms\Components\Select::make('primary_amount_unit')
                            ->label('Primary Amount Unit')
                            ->required()->columnSpan(2)
                            ->options([
                                // Mass units
                                'mcg' => 'mcg (microgram)',
                                'mg' => 'mg (milligram)',
                                'g' => 'g (gram)',
                                'kg' => 'kg (kilogram)',
                                'oz' => 'oz (ounce)',
                                'lb' => 'lb (pound)',
                                'st' => 'st (stone)',
                                // Fluid/volume units
                                'ml' => 'ml (milliliter)',
                                'l' => 'l (liter)',
                                'tsp' => 'tsp (teaspoon)',
                                'tbsp' => 'tbsp (tablespoon)',
                                'fl oz' => 'fl oz (fluid ounce)',
                                'cup' => 'cup',
                                'pt' => 'pt (pint)',
                                'qt' => 'qt (quart)',
                                'gal' => 'gal (gallon)',
                
                                // Other common units
                                'piece' => 'piece',
                                'serving' => 'serving',
                                'slice' => 'slice',
                                'portion' => 'portion',
                                'drop' => 'drop',
                                'pinch' => 'pinch',
                                'sheet' => 'sheet',
                                'package' => 'package',
                                'container' => 'container',
                                'can' => 'can',
                                'bottle' => 'bottle',
                                'tin' => 'tin',
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
                        Forms\Components\Toggle::make('multiply_values')
                            ->label('Multiply Values in Final table')->default(false),
                    ]),
                Forms\Components\TextInput::make('list_order')
                    ->label('List Order')
                    ->numeric()
                    ->nullable()
                    ->default($nextOrder)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $user = Auth::user();
        $defaultLang = $user->default_lang ?? 'Eng';
         //var_dump($defaultLang);

        return $infolist->schema([
            Infolists\Components\Section::make('Diet Information')
                ->schema([
                    Infolists\Components\TextEntry::make('list_order')
                        ->label('List Order'),
                    Infolists\Components\TextEntry::make('DietName_en')
                        ->label('Diet Name (English)')
                        ->visible($defaultLang === 'Eng' || $defaultLang === null),
                    Infolists\Components\TextEntry::make('DietName_si')
                        ->label('Diet Name (Sinhala)')
                        ->placeholder('Not specified')
                        ->visible($defaultLang === 'Sin'),
                    Infolists\Components\TextEntry::make('DietName_tm')
                        ->label('Diet Name (Tamil)')
                        ->placeholder('Not specified')
                        ->visible($defaultLang === 'Tam'),
                    // Show all languages section if needed
                    Infolists\Components\TextEntry::make('all_languages')
                        ->label('All Translations')
                        ->formatStateUsing(function ($record) {
                            $translations = [];
                            if ($record->DietName_en) $translations[] = "English: {$record->DietName_en}";
                            if ($record->DietName_si) $translations[] = "Sinhala: {$record->DietName_si}";
                            if ($record->DietName_tm) $translations[] = "Tamil: {$record->DietName_tm}";
                            return implode(' | ', $translations);
                        })
                        ->columnSpanFull()
                        ->visible(fn () => $defaultLang === null || !in_array($defaultLang, ['Eng', 'Sin', 'Tam'])),
                    Infolists\Components\TextEntry::make('category')
                        ->label('Food Categories')
                        ->badge()
                        ->formatStateUsing(function ($state) {
                            if (is_array($state)) {
                                return array_map(function($cat) {
                                    return ucwords(str_replace('_', ' ', $cat));
                                }, $state);
                            }
                            return $state ? ucwords(str_replace('_', ' ', $state)) : 'No category';
                        })
                        ->columnSpanFull(),
                    Infolists\Components\IconEntry::make('active')
                        ->label('Status')
                        ->boolean()
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->trueColor('success')
                        ->falseColor('danger'),
                    
                ])
                ->columns(2),
            
            Infolists\Components\Section::make('Primary Amount Settings')
                ->schema([
                    Infolists\Components\TextEntry::make('primary_amount_value')
                        ->label('Primary Amount Value'),
                    Infolists\Components\TextEntry::make('primary_amount_unit')
                        ->label('Primary Amount Unit'),
                    Infolists\Components\IconEntry::make('multiply_values')
                        ->label('Multiply Values in Final Table')
                        ->boolean()
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->trueColor('success')
                        ->falseColor('gray'),
                ])
                ->columns(columns: 2)
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $defaultLang = $user->default_lang ?? 'Eng';
        
        return $table
            ->reorderable('list_order')
            ->columns([
                Tables\Columns\TextColumn::make('list_order')
                    ->label('List Order')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('DietName_en')
                    ->label('Diet Name (English)')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault($defaultLang !== 'Eng'),
                Tables\Columns\TextColumn::make('DietName_si')
                    ->label('Diet Name (Sinhala)')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault($defaultLang !== 'Sin'),
                Tables\Columns\TextColumn::make('DietName_tm')
                    ->label('Diet Name (Tamil)')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault($defaultLang !== 'Tam'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categories')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if (is_array($record->category)) {
                            return array_map(function($cat) {
                                return ucwords(str_replace('_', ' ', $cat));
                            }, $record->category);
                        }
                        return $record->category ? [ucwords(str_replace('_', ' ', $record->category))] : ['No category'];
                    })
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Active')
                    ->disabled(function () {
                        $user = Auth::user();
                        return !($user && userHasPermission($user, 'edit.SimpleDiet_Simple-Panel'));
                    })
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('primary_amount_value')
                    ->label('Primary Amount Value')
                    ->sortable()
                    ->columnSpan(3)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('primary_amount_unit')
                    ->label('Primary Amount Unit')
                    ->sortable()
                    ->columnSpan(2)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                
            ])
                        ->defaultSort('list_order')
              ->paginated([ 25, 50, 100, 'all'])
               ->defaultPaginationPageOption(25)
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Filter by Category')
                    ->multiple()
                    ->options([
                        'grains_cereals' => 'General Grains & Cereals',
                        'rice' => 'Rice & Rice Products',
                        'wheat' => 'Wheat & Wheat Products',
                        'bread' => 'Bread & Bakery Products',
                        'pasta' => 'Pasta & Noodles',
                        'meat' => 'Meat & Poultry',
                        'seafood' => 'Fish & Seafood',
                        'eggs' => 'Eggs & Egg Products',
                        'legumes' => 'Legumes & Beans',
                        'nuts_seeds' => 'Nuts & Seeds',
                        'protein_alternatives' => 'Protein Alternatives',
                        'milk' => 'Milk & Milk Products',
                        'fruits' => 'General Fruits',
                        'vegetables' => 'General Vegetables',
                        'leafy_greens' => 'Leafy Greens',
                        'root_vegetables' => 'Root Vegetables',
                        'citrus_fruits' => 'Citrus Fruits',
                        'tropical_fruits' => 'Tropical Fruits',
                        'fats_oils' => 'General Fats & Oils',
                        'cooking_oils' => 'Cooking Oils',
                        'butter_margarine' => 'Butter & Margarine',
                        'snacks' => 'General Snacks',
                        'sweets_desserts' => 'Sweets & Desserts',
                        'chocolate' => 'Chocolate & Confectionery',
                        'beverages' => 'General Beverages',
                        'water' => 'Water',
                        'tea_coffee' => 'Tea & Coffee',
                        'juices' => 'Fruit & Vegetable Juices',
                        'soft_drinks' => 'Soft Drinks',
                        'condiments' => 'Condiments & Sauces',
                        'spices_herbs' => 'Spices & Herbs',
                        'vinegar' => 'Vinegar & Acidic Condiments',
                        'sports_nutrition' => 'Sports Nutrition',
                        'supplements' => 'Dietary Supplements',
                        'meal_replacements' => 'Meal Replacements',
                        'raw' => 'Raw Foods',
                        'cooked' => 'Cooked Foods',
                        'steamed' => 'Steamed',
                        'fried' => 'Fried Foods',
                        'grilled' => 'Grilled Foods',
                        'baked' => 'Baked Foods',
                        'vegetarian' => 'Vegetarian',
                        'vegan' => 'Vegan',
                        'gluten_free' => 'Gluten-Free',
                        'dairy_free' => 'Dairy-Free',
                        'low_sodium' => 'Low Sodium',
                        'low_fat' => 'Low Fat',
                        'low_sugar' => 'Low Sugar',
                        'keto_friendly' => 'Keto-Friendly',
                        'diabetic_friendly' => 'Diabetic-Friendly',
                        'processed_foods' => 'Processed Foods',
                        'organic' => 'Organic',
                        'fast_food' => 'Fast Food',
                        'street_food' => 'Street Food',
                        'instant_foods' => 'Instant Foods',
                        'preserved_foods' => 'Preserved Foods',
                        'fermented_foods' => 'Fermented Foods',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        
                        return $query->where(function (Builder $query) use ($data) {
                            foreach ($data['values'] as $category) {
                                $query->orWhereJsonContains('category', $category);
                            }
                        });
                    }),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status')
                    ->placeholder('All diet types')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewSimpleDiet::route('/{record}'),
            'edit' => Pages\EditSimpleDiet::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.SimpleDiet_Simple-Panel') : false;
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.SimpleDiet_Simple-Panel') : false;
    }
    
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.SimpleDiet_Simple-Panel') : false;
    }
    
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.SimpleDiet_Simple-Panel') : false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.SimpleDiet_Simple-Panel') : false;
    }
    
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.SimpleDiet_Simple-Panel') : false;
    }
}
