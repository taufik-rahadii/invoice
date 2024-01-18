<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceOverview;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\ProductPrice;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

function convertToIDR($number)
{
    // Format the number with two decimal places and use IDR as the currency symbol
    $formattedNumber = number_format($number, 2, ',', '.');

    // Add IDR as the currency symbol
    $idrString = 'IDR ' . $formattedNumber;

    return $idrString;
}

function generateInvoiceCode(string $sellerName)
{
    // Extract the first letter of each word in the seller's name
    $initials = implode('', array_map('strtoupper', array_map('substr', explode(' ', $sellerName), array_fill(0, count(explode(' ', $sellerName)), 0))));

    // Get the current date in YYYYMMDD format
    $currentDate = date('YmdHis');

    // Create the invoice code by combining initials, current date, invoice number, and additional identifier
    $invoiceCode = 'AIFSHN-' . $initials . '-' . $currentDate;

    return $invoiceCode;
}

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;


    protected static ?string $recordTitleAttribute = 'Nota';
    protected static ?string $label = 'Nota';
    protected static ?string $navigationLabel = 'Nota';

    protected static ?string $navigationGroup = 'Reseller';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isGloballySearchable = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Info Invoice
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Info Nota')
                        ->schema([
                            Forms\Components\Select::make('seller_id')
                                ->relationship(
                                    name: 'seller',
                                    titleAttribute: 'name',
                                    modifyQueryUsing:
                                    fn(Builder $query) => $query->whereHas('role', function ($query) {
                                        $query->where('code', 'seller');
                                    })
                                )
                                ->reactive()
                                ->afterStateUpdated(
                                    function ($state, Forms\Set $set) {
                                        $userName = User::find($state)?->name;
                                        $generatedCode = generateInvoiceCode($userName);

                                        return $set('code', $generatedCode);
                                    }
                                )
                                ->required(),
                            Forms\Components\TextInput::make('code')
                                ->label('Kode')
                                ->required()
                                ->readonly()
                                ->reactive()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('no')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Forms\Components\Section::make('Detail Nota')
                        ->schema([
                            Forms\Components\HasManyRepeater::make('details')
                                ->schema([
                                    Forms\Components\Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->required()
                                        ->label('Produk')
                                        ->live(),
                                    Forms\Components\Select::make('product_price_id')
                                        ->relationship(name: 'productPrices', titleAttribute: 'name')
                                        ->label('Harga Produk')
                                        ->model(ProductPrice::class)
                                        ->options(function (Get $get) {
                                            $list = ProductPrice::query()->where('product_id', $get('product_id'))->pluck('selling_price', 'id')->transform(function ($record, $key) {

                                                return convertToIDR($record);
                                            });
                                            return $list;
                                        })
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')->nullable(),
                                            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
                                            Forms\Components\Select::make('product_id')
                                                ->relationship(name: 'product', titleAttribute: 'Produk')
                                        ])
                                        ->preload()
                                        ->required()
                                        ->reactive()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->distinct()
                                        ->afterStateUpdated(
                                            function ($state, Forms\Set $set, Forms\Get $get) {
                                                $productPrice = ProductPrice::find($state)?->selling_price ?? 0;
                                                $qty = intval($get('qty'));

                                                $totalPrice = $productPrice * $qty;
                                                $toCurrencyStr = convertToIDR($totalPrice);
                                                return $set('total_price', $toCurrencyStr);
                                            }
                                        ),
                                    Forms\Components\TextInput::make('qty')
                                        ->label('Total Kuantiti Barang')
                                        ->required()
                                        ->numeric()
                                        ->default(1)
                                        ->reactive()
                                        ->afterStateUpdated(
                                            function ($state, Forms\Set $set, Forms\Get $get) {
                                                $productPrice = ProductPrice::find($get('product_price_id'))?->selling_price ?? 0;
                                                $qty = intval($state);

                                                $totalPrice = $productPrice * $qty;
                                                $toCurrencyStr = convertToIDR($totalPrice);
                                                return $set('total_price', $toCurrencyStr);
                                            }
                                        ),
                                    Forms\Components\TextInput::make('total_price')
                                        ->label('Total Harga Produk')
                                        ->readonly()
                                        ->reactive()
                                        ->required()
                                ])
                                ->required()
                        ])
                ])
                // ->columns(['lg' => 1]),
            ]);
    }

    public static function getProductPricesByProductId(?string $productId)
    {
        if (isset($productId)) {
            $list = ProductPrice::query()->where('product_id', $productId)->select(['id', 'name', 'selling_price']);
            return $list;
        }

        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'detail' => Pages\DetailInvoice::route('/{record}/detail'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            InvoiceOverview::class,
        ];
    }
}
