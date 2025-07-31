<?php

namespace App\Filament\Widgets;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BooksOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $books = Book::all()->count();
        $authors = Author::all()->count();
        $categories = Category::all()->count();
        return [
            Stat::make('Books', $books)->icon('heroicon-o-book-open'),
            Stat::make('Authors', $authors)->icon('grommet-user-manager'),
            Stat::make('Categories', $categories)->icon('iconsax-lin-category'),
        ];
    }
}
