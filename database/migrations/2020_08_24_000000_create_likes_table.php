<?php /** @noinspection PhpUnused */

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SoluzioneSoftware\Laravel\Likable\Traits\ResolvesContracts;

class CreateLikesTable extends Migration
{
    use ResolvesContracts;

    /**
     * Run the migrations.
     * @throws BindingResolutionException
     */
    public function up()
    {
        $liker = static::resolveLikerContract();
        Schema::create(static::resolveLikeContract()->getTable(), function (Blueprint $table) use ($liker) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger($liker->getForeignKey());
            $table->morphs('likable');
            $table->boolean('liked');
            $table->timestamps();

            $table->unique([$liker->getForeignKey(), 'likable_id', 'likable_type']);

            $table
                ->foreign($liker->getForeignKey())
                ->references($liker->getKeyName())
                ->on($liker->getTable())
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(static::resolveLikeContract()->getTable());
    }
}
