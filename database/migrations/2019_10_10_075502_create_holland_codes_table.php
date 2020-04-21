<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollandCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holland_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->text('explanation');
            $table->timestamps();
        });

        DB::table('holland_codes')->insert(
            array(
                'code' => 'R',
                'name' => 'Realistic',
                'explanation' => 'Orang-orang dengan tipe Realistic pada umumnya menyukai bekerja dengan benda ketimbang dengan manusia. Mereka sering dideskripsikan sebagai orang yang penuh kesungguhan, bijaksana, giat, cermat, rendah hati, gigih, dan jujur.'
            ),
            array(
                'code' => 'I',
                'name' => 'Investigative',
                'explanation' => 'Orang-orang dengan tipe Investigative biasanya lebih menyukai pekerjaan yang melibatkan ide-ide ketimbang manusia maupun benda. Mereka biasanya dideskripsikan sebagai orang yang logis, penuh rasa ingin tahu, teliti, berintelektual, penuh hati-hati, mandiri, pendiam dan rendah hati.'
            ),
            array(
                'code' => 'A',
                'name' => 'Artistic',
                'explanation' => 'Tipe Aritstic biasanya menyukai pekerjaan yang melibatkan ide-ide daripada barang. Mereka biasanya dideskripsikan sebagai orang yang terbuka, kreatif, mandiri, penuh emosi, impulsif dan apa adanya.'
            ),
            array(
                'code' => 'S',
                'name' => 'Social',
                'explanation' => 'Tipe Social biasanya menyukai pekerjaan yang melibatkan banyak orang daripada benda. Mereka kerap dideskripsikan sebagai orang yang suka menolong, pengertian, bertanggung jawab, hangat, koorperatif, meyakinkan, ramah, baik hati, dan penyabar.'
            ),
            array(
                'code' => 'E',
                'name' => 'Enterprising',
                'explanation' => 'Tipe Enterprising pada umumnya menyukai pekerjaan yang melibatkan ide serta banyak orang, daripada benda. Mereka biasanya dideskripsikan sebagai orang yang ramah, menyukai petualangan, penuh energi, optimis, berjiwa sosial dan penuh kepercayaan diri.'
            ),
            array(
                'code' => 'C',
                'name' => 'Conventional',
                'explanation' => 'Tipe Conventional pada umumnya senang bekerja dengan dokumen dan angkaMereka biasanya didekskripsikan sebagai orang yang giat, penuh hati-hati, cermat, efeisien, rapi, tertib dan presisten.'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holland_codes');
    }
}
