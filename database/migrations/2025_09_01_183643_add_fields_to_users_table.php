<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'full_name');

            $table->string('phone_number')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone_number');
            $table->string('image')->nullable()->after('address');

            $table->index('email');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'address', 'image']);

            $table->renameColumn('full_name', 'name');

            $table->dropIndex(['email']);
            $table->dropIndex(['phone_number']);
        });
    }
};
