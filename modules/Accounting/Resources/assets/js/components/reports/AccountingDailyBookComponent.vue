<template>
    <div class="form-horizontal">
        <div class="card-body">
            <accounting-show-errors ref="errorsDialyBook" />
            <div class="row">
                <div class="col-3" id="helpDailyBookInitDate">
                    <div class="is-required">
                        <label class="control-label">Fecha inicial</label>
                        <input type="date" class="form-control input-sm" v-model="dateIni">
                    </div>
                </div>
                <div class="col-3" id="helpDailyBookEndDate">
                    <div class="is-required">
                        <label class="control-label">Fecha final</label>
                        <input type="date" class="form-control input-sm" v-model="dateEnd" :min="dateIni?dateIni:''" :disabled="dateIni?false:true">
                    </div>
                </div>
                <div class="col-3" id="helpDailyBookCurrency">
                    <div class="is-required">
                        <label class="control-label">Tipo de moneda</label>
                        <select2 :options="currencies" v-model="currency"></select2>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary btn-sm" data-toggle="tooltip" v-has-tooltip title="Generar Reporte" v-on:click="OpenPdf(getUrlReport(), '_blank')" id="helpDailyBookGenerateReport">
                <span>Generar reporte</span>
                <i class="fa fa-print"></i>
            </button>
            <!-- <button class="btn btn-primary btn-sm" data-toggle="tooltip" v-has-tooltip title="Generar y Firmar Reporte" v-on:click="OpenPdf(getUrlReportSign(), '_blank')" id="helpDailyBookGenerateReportSign">
                <span>Generar y firmar reporte</span>
                <i class="fa fa-print"></i>
            </button> -->
        </div>
    </div>
</template>
<script>
export default {
  props: {
    currencies: {
      type: Array,
      default() {
        return [];
      }
    }
  },
  data() {
    return {
      url: `${window.app_url}/accounting/report/dailyBook/pdf/`,
      urlSign: `${window.app_url}/accounting/report/dailyBookSign/pdf/`,
      dateIni: '',
      dateEnd: '',
      currency: '',
    };
  },
  methods: {
    /**
         * Formatea la url para el reporte
         *
         * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
         * @return {string} url para el reporte
         */
    getUrlReport: function() {

      var errors = [];
      if (!this.dateIni) {
        errors.push('La fecha inicial es obligatorio.');
      }
      if (!this.dateEnd) {
        errors.push('La fecha final es obligatorio.');
      }
      if (!this.currency) {
        errors.push('El tipo de moneda es obligatorio.');
      }

      if (errors.length > 0) {
        this.$refs.errorsDialyBook.showAlertMessages(errors);
        return;
      }
      this.$refs.errorsDialyBook.reset();

      var dateIni = this.dateIni;
      var dateEnd = this.dateEnd;
      var info = (this.dateIni <= this.dateEnd) ? (dateIni + '/' + dateEnd) : (dateEnd + '/' + dateIni);
      var url = this.url + info + '/' + this.currency;
      return url;
    },
    /**
         * Formatea la url para el reporte
         *
         * @author Juan Rosas <jrosas@cenditel.gob.ve | juan.rosasr01@gmail.com>
         * @return {string} url para el reporte
         */
    getUrlReportSign: function() {

      var errors = [];
      if (!this.dateIni) {
        errors.push('La fecha inicial es obligatorio.');
      }
      if (!this.dateEnd) {
        errors.push('La fecha final es obligatorio.');
      }
      if (!this.currency) {
        errors.push('El tipo de moneda es obligatorio.');
      }

      if (errors.length > 0) {
        this.$refs.errorsDialyBook.showAlertMessages(errors);
        return;
      }
      this.$refs.errorsDialyBook.reset();

      var dateIni = this.dateIni;
      var dateEnd = this.dateEnd;
      var info = (this.dateIni <= this.dateEnd) ? (dateIni + '/' + dateEnd) : (dateEnd + '/' + dateIni);
      var url = this.urlSign + info + '/' + this.currency;
      return url;
    },
  }
};
</script>
