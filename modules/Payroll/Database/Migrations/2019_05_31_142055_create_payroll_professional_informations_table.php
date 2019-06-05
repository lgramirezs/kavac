<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollProfessionalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_professional_informations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('payroll_instruction_degree_id')->unsigned()
                  ->comment('identificador del grado de instrucción que pertenece a la información profesional');
            $table->foreign('payroll_instruction_degree_id')->references('id')->on('payroll_instruction_degrees')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->integer('profession_id')->unsigned()
                  ->comment('identificador de la profesión que pertenecen a la información profesional');
            $table->foreign('profession_id')->references('id')->on('professions')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->string('instruction_degree_name', 100)->nullable()->comment('Nombre en caso de elegir en Grado de Instrucción: Especialización, Maestría, Doctorado');

            $table->boolean('is_student')->default(false)->comment('Establece si el trabajdor es estudiante o no');
            $table->text('study_program_name')->nullable()->comment('Nombre del programa de estudio');
            $table->text('class_schedule')->nullable()->comment('Descripción de la cuenta');

            $table->integer('payroll_language_id')->unsigned()
                  ->comment('identificador del idioma que pertenece a la información profesional');
            $table->foreign('payroll_language_id')->references('id')->on('payroll_languages')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->integer('payroll_language_level_id')->unsigned()
                  ->comment('identificador de nivel del idioma que pertenece a la información profesional');
            $table->foreign('payroll_language_level_id')->references('id')->on('payroll_language_levels')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->integer('payroll_staff_id')->unsigned()
                  ->comment('identificador del trabajador que pertenece a la información profesional');
            $table->foreign('payroll_staff_id')->references('id')->on('payroll_staffs')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes()->comment('Fecha y hora en la que el registro fue eliminado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_professional_informations');
    }
}
