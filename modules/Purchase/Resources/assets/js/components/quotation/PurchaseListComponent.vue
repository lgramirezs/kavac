<template>
    <section>
        <v-client-table :columns="columns" :data="records" :options="table_options">
              <a slot="relatable[0].purchase_requirement_item.purchase_requirement.code" slot-scope="props" target="_blank">
                          
                                   <span v-for="codes in getUniqueCars(props.row.relatable)" :key="codes">
                                    <span >
                                        {{ codes}},                                        
                                    </span>
                                </span>
                        
                            </a>
            <div slot="purchase_supplier.purchase_supplier_object" slot-scope="props" class="text-center">
                <div v-if="props.row.purchase_supplier.purchase_supplier_object">
                    <div v-if="props.row.purchase_supplier.purchase_supplier_object.type == 'S'">
                        <strong>Servicios / {{ props.row.purchase_supplier.purchase_supplier_object.name }}</strong>
                    </div>
                    <div v-else-if="props.row.purchase_supplier.purchase_supplier_object.type == 'O'">
                        <strong>Obras / {{ props.row.purchase_supplier.purchase_supplier_object.name }}</strong>
                    </div>
                    <div v-else-if="props.row.purchase_supplier.purchase_supplier_object.type == 'B'">
                        <strong>Bienes / {{ props.row.purchase_supplier.purchase_supplier_object.name }}</strong>
                    </div>
                </div>
            </div>
           
            <div slot="id" slot-scope="props" class="text-center">
                <div class="d-inline-flex">
                    <purchase-quotation-show 
                        :id="props.row.id" />
                    <!--<purchase-quotation-show 
                        :id="props.row.id" 
                        :route_show="'/purchase/quotation/'+props.row.id" />-->
                    <button @click="editForm(props.row.id)"
                            class="btn btn-warning btn-xs btn-icon btn-action"
                            title="Modificar registro"
                            data-toggle="tooltip" v-has-tooltip>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button @click="deleteRecord(props.row.id, '/purchase/quotation')" class="btn btn-danger btn-xs btn-icon btn-action" title="Eliminar registro" data-toggle="tooltip" v-has-tooltip>
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </v-client-table>
        <hr>
    </section>
</template>
<script>
export default {
    props: {
        record_lists: {
            type: Array,
            default: function() {
                return [];
            }
        },
    },
    data() {
        return {
            records: [],
            columns: [
                'relatable[0].purchase_requirement_item.purchase_requirement.code',
                'purchase_supplier.name',             
                'currency.name',
                'id'
            ],
        }
    },
      methods: {
           getUniqueCars(id) {
                  return id.map(x => x.purchase_requirement_item.purchase_requirement.code ).filter((v,i,s) => s.indexOf(v) === i)
                 },
      },
    created() {
        this.table_options.headings = {
            'relatable[0].purchase_requirement_item.purchase_requirement.code':"Código del requerimiento",
            'purchase_supplier.name': 'Proveedor',             
            'currency.name': 'tipo de moneda',
            'id': 'ACCIÓN'
        };
        this.table_options.columnsClasses = {
            'relatable[0].purchase_requirement_item.purchase_requirement.code':'col-xs-3 text-center',
            'purchase_supplier.name': 'col-xs-3',            
            'currency.name': 'col-xs-3 text-center',
            'id': 'col-xs-1'
        };
        this.table_options.sortable = ['purchase_supplier.name', 'currency.name'];
        this.table_options.filterable = ['purchase_supplier.name', 'currency.name'];
    },
    
    mounted() {
        this.records = this.record_lists;
    },
    computed:{
      
      
    },
};
</script>
