<?php
declare(strict_types=1);

namespace Plexikon\DevApp\Projection\User;

use Illuminate\Database\Schema\Blueprint;
use Plexikon\Chronicle\Support\Projection\HasConnectionOperation;
use Plexikon\Chronicle\Support\Projection\ReadModelConnection;
use Plexikon\DevApp\Projection\Table;

final class UserReadModel extends ReadModelConnection
{
    use HasConnectionOperation;

    protected function up(): callable
    {
        return function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestampsTz();
        };
    }

    protected function tableName(): string
    {
        return Table::USER_TABLE;
    }
}
