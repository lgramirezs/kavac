<template>
    <div id="PayrollProfessionalInfo" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="PayrollProfessionalInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:60rem">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h6>
                        <i class="icofont icofont-read-book ico-2x"></i>
                         Información Detallada de los Datos Profesionales
                    </h6>
                </div>

                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Nombres del trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_staff ? record.payroll_staff.first_name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Apellidos del Trabajador:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_staff ? record.payroll_staff.last_name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Cédula de identidad:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_staff ? record.payroll_staff.id_number : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Grado de instrucción:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ record.payroll_instruction_degree ? record.payroll_instruction_degree.name : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Profesión:</strong>
                                        <div v-if="record.payroll_studies != 0">
                                            <div v-for="profession in professions" :key="profession.id">
                                                <div v-for="study in record.payroll_studies" :key="study.id">
                                                    <div class="row" style="margin-left: 1px;">
                                                        <span class="col-md-12">
                                                            {{ study.profession_id == profession.id ? profession.text : '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-center">Estudios universitarios</h6><br>
                            <div class="row" v-for="study in record.payroll_studies">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Nombre de la Universidad:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ study.university_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Fecha de graduación:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ study.graduation_year }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Tipo de estudios:</strong>
                                        <div v-for="study_type in payroll_study_types">
                                            <div class="row" style="margin-left: 1px;">
                                                <span class="col-md-12">
                                                    {{ study.payroll_study_type_id == study_type.id ? study_type.text : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Profesión:</strong>
                                        <div v-for="profession in professions" :key="profession.id">
                                            <div class="row" style="margin-left: 1px;">
                                                <span class="col-md-12">
                                                    {{ study.profession_id == profession.id ? profession.text : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>¿Es Estudiante?</strong>
                                        <div class="row" style="margin-left: 1px;">
                                            <span class="col-md-12">
                                                {{ record.is_student ? 'Si' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="record.is_student">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Tipo de estudio que cursa</strong>
                                            <div v-for="study_type in payroll_study_types">
                                                <div class="row" style="margin-left: 1px;">
                                                    <span class="col-md-12">
                                                        {{ study.payroll_study_type_id == study_type.id ? study_type.text : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-center">Detalles del idioma</h6><br>
                            <div class="row" v-for="(payroll_language, index) in record.payroll_languages" :key="index">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong>Idioma:</strong>
                                        <div class="row" style="margin: 1px 0">
                                            <span class="col-md-12">
                                                {{ payroll_language.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong>Nivel del lenguaje:</strong>
                                        <div v-for="level in payroll_language_levels">
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ payroll_language.pivot.payroll_language_level_id == level.id ? level.text : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-center">Capacitación y Reconocimientos</h6><br>
                            <div v-if="record.payroll_acknowledgment">
                                <div class="row" v-for="file in record.payroll_acknowledgment.payroll_acknowledgment_files">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Tipo de reconocimiento</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ file.name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Certificado del reconocimiento:</strong>
                                            <div v-for="urls in file.documents">
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ urls.url  }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="record.payroll_course">
                                <div class="row" v-for="course in record.payroll_course.payroll_course_files">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Nombre del curso realizado</strong>
                                            <div class="row" style="margin: 1px 0">
                                                <span class="col-md-12">
                                                    {{ course.name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Certificado del curso realizado:</strong>
                                            <div v-for="cert in course.documents">
                                                <div class="row" style="margin: 1px 0">
                                                    <span class="col-md-12">
                                                        {{ cert.url  }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    export default {
        data() {
            return {
                record: {
                    id: '',
                    payroll_staff_id: '',
                    payroll_instruction_degree_id: '',
                    instruction_degree_name: '',
                    is_student: '',
                    payroll_study_type_id: '',
                    study_program_name: '',
                    class_schedule_ids: [],
                    professions: [],
                    payroll_languages: [],
                    payroll_cou_ack_files: [],
                    payroll_studies:[],
                },
                errors: [],
                //payroll_staffs: [],
                payroll_professional: [],
                payroll_instruction_degrees: [],
                professions: [],
                json_professions: [],
                payroll_study_types: [],
                payroll_languages: [],
                payroll_language_levels: [],
                payroll_class_schedule: '',
                payroll_cou_ack_files: [],
            }
        },
        created() {
            this.getPayrollStudyTypes();
            this.getProfessions();
            this.getPayrollLanguageLevels();
        },
        methods: {
            /**
             * Método que borra todos los datos del formulario
             * 
             * @author  Pablo Sulbarán <dcontreras@cenditel.gob.ve>
             */
            reset() {
            },  
        },
    }
</script>