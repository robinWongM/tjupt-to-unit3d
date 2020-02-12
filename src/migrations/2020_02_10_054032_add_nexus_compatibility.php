<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNexusCompatibility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
        });
        Schema::table('torrents', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_user_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_category_id')
                ->nullable()
                ->default(null);
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_user_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_torrent_id')
                ->nullable()
                ->default(null);
        });
        if (!Schema::hasColumn('torrents', 'subhead')) {
            Schema::table('torrents', function (Blueprint $table) {
                $table->string('subhead')
                    ->nullable()
                    ->default(null);
            });
        }
        Schema::table('forums', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_parent_id')
                ->nullable()
                ->default(null);
            $table->string('nexus_permission')
                ->nullable()
                ->default(null);
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_user_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_parent_id')
                ->nullable()
                ->default(null);
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_user_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_parent_id')
                ->nullable()
                ->default(null);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->integer('nexus_id')
                ->nullable()
                ->default(null);
            $table->integer('nexus_user_id')
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
        });
        Schema::table('torrents', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_user_id');
            $table->dropColumn('nexus_category_id');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_user_id');
            $table->dropColumn('nexus_torrent_id');
        });
        Schema::table('forums', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_parent_id');
            $table->dropColumn('nexus_permission');
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_user_id');
            $table->dropColumn('nexus_parent_id');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_user_id');
            $table->dropColumn('nexus_parent_id');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('nexus_id');
            $table->dropColumn('nexus_user_id');
        });
    }
}
