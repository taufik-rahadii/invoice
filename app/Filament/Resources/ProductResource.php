<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductUnitMeasurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Info')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Product Name')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('code')
                            ->label('SKU')
                            ->required()
                            ->rules(['unique:products,code', 'max:50'])
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->nullable()
                            ->columnSpanFull(),

                    ])->columns(2),

                    Forms\Components\Section::make('Relation')->schema([
                        Forms\Components\Select::make('product_unit_id')
                            ->label('Unit Of Measurement')
                            ->relationship(name: 'unit_measurement', titleAttribute: 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('piece_per_unit')->required(),
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->columnSpanFull()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                            ]),
                    ])

                ])
                    ->columnSpan(['lg' => 2]),


                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Image')->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Product Image')
                            ->nullable()
                            ->rules(['extension:jpg,png']),
                    ])
                ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\HasManyRepeater::make('prices')->schema([
                        Forms\Components\TextInput::make('name')->nullable(),
                        Forms\Components\TextInput::make('selling_price')->numeric()->required(),
                    ])
                        ->defaultItems(1)
                ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Product Name')
                            ->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('code')
                            ->label('SKU'),
                        Tables\Columns\TextColumn::make('category.name')->label('Product Category'),
                    ]),
                ]),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('unit_measurement.name')->label('Unit Measurement'),
                    ]),
                ])->collapsible(),
            ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
