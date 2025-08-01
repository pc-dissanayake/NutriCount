<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpleDietResource\Pages;
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
    protected static ?string $navigationGroup = 'Diet Management';
    
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
        return $infolist->schema([
            Infolists\Components\Section::make('Diet Information')
                ->schema([
                    Infolists\Components\TextEntry::make('DietName_en')
                        ->label('Diet Name (English)'),
                    Infolists\Components\TextEntry::make('DietName_si')
                        ->label('Diet Name (Sinhala)')
                        ->placeholder('Not specified'),
                    Infolists\Components\TextEntry::make('DietName_tm')
                        ->label('Diet Name (Tamil)')
                        ->placeholder('Not specified'),
                    Infolists\Components\IconEntry::make('active')
                        ->label('Status')
                        ->boolean()
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->trueColor('success')
                        ->falseColor('danger'),
                    Infolists\Components\TextEntry::make('list_order')
                        ->label('List Order'),
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
                ->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('list_order')
            ->columns([
                Tables\Columns\TextColumn::make('list_order')
                    ->label('List Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false, isAlwaysVisible: true),
   Tables\Columns\TextColumn::make(name: 'DietName_en')->label('Diet Name (English)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_si')->label('Diet Name (Sinhala)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_tm')->label('Diet Name (Tamil)')->searchable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Active')
                    ->disabled(fn () => !Auth::user()?->can('edit.SimpleDiet')),
                
                Tables\Columns\TextColumn::make('primary_amount_value')
                    ->label('Primary Amount Value')
                    ->sortable()
                    ->columnSpan(3),
                Tables\Columns\TextColumn::make('primary_amount_unit')
                    ->label('Primary Amount Unit')
                    ->sortable()
                    ->columnSpan(2),
            ])
                        ->defaultSort('list_order')
              ->paginated([ 25, 50, 100, 'all'])
               ->defaultPaginationPageOption(25)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.SimpleDiet') : false;
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.SimpleDiet') : false;
    }
    
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.SimpleDiet') : false;
    }
    
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.SimpleDiet') : false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.SimpleDiet') : false;
    }
    
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.SimpleDiet') : false;
    }
}
