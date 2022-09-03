<template>
  <div id="app">
    <b-container class="bv-example-row">
        <b-row class="mt-4 mb-4">
           <b-col>
               <b-button v-b-modal.modal-create v-if="!isAdded">Create</b-button> 
               <b-button class="me-2" v-if="isAdded" @click="validateSubmit()">Validate</b-button> 
               <b-button v-if="isAdded" @click="cancelValidate()">Cancel</b-button> 
            </b-col> 
        </b-row>
        <b-row>
            <b-col>
                <!--List active-->
                <list :title="'ToDo Active'" :total="totalActive" :items="listActive"></list>
            </b-col>
            <b-col>
                <!--List completed-->
                <list :title="'ToDo Terminated'" :total="totalCompleted" :items="listCompleted"></list>
            </b-col>
        </b-row>

        <!--Modal Create-->
        <b-modal
            id="modal-create"
            ref="modal"
            title="Create a ToDo"
            >
            <form ref="form">
                <b-form-group>
                    <label for="title" class="required">Title <span>*</span></label>
                    <input type="text" id="title" v-model="$v.item.title.$model" class="form-control"
                    :class="[$v.item.title.$dirty ? ($v.item.title.$invalid ? ' is-invalid ' :  ' ') : ' ' ]"/>
                    <div v-if="$v.item.title.$invalid"
                            :class="$v.item.title.$invalid ? ' invalid-feedback ' : ''">
                        <span v-if="!$v.item.title.required">This field is required</span>
                    </div>
                </b-form-group>
                <b-form-group>
                    <label for="description">Description</label>
                    <textarea id="description" class="form-control" rows="6" v-model="item.description"></textarea>
                </b-form-group>
            </form>
            <template #modal-footer>
                <b-button variant="danger" @click="cancel()">
                    Cancel
                </b-button>
                <b-button type="submit" variant="success" @click="onSubmitCreate()">
                    Add
                </b-button>
            </template>
        </b-modal>
    </b-container>
  </div>
</template>

<script>
import List from './components/List.vue';
import axios from 'axios'
import {validationMixin} from 'vuelidate'
import { required} from "vuelidate/lib/validators";
export default {
  name: 'app',
  components: {
    List
  },
  data() {
    return {
      totalActive : 0,
      totalCompleted : 0,
      listActive: [],
      listCompleted: [],
      item : {
        title: '',
        description: ''
      },
      isAdded : false
    };
  },
  mixins: [validationMixin],
  validations: {
    item: {
        title: {
            required
        },
    }
  },
  created() {
    this.getList();
  },
  methods: {
    getList() {
        axios
      .get('http://localhost/vue-js-php-todo-list/api/')
      .then(response => {
        this.listActive = response.data.uncompleted;
        this.totalActive = this.listActive.length;
        this.listCompleted = response.data.completed;
        this.totalCompleted = this.listCompleted.length;
      })
      .catch(err => {
        console.log(err)
      });
    },

    cancel(){
        this.item.title = '';
        this.item.description = '';
        this.$bvModal.hide('modal-create');
        this.$v.$reset ();
    },

    onSubmitCreate(){
        this.$v.$touch ();
        if (!this.$v.$invalid) {
            this.listActive.push(this.item);
            this.$bvModal.hide('modal-create');
            this.$v.$reset ();
            this.isAdded = true;
        }
    },

    cancelValidate(){
        this.isAdded = false;
        let index = this.totalActive;
        this.listActive.splice(index, 1);
        this.item.title = '';
        this.item.description = '';
        this.$v.$reset ();
    },

    validateSubmit(){
        axios
      .post('http://localhost/vue-js-php-todo-list/api/create/task', this.item, {
        headers: {
            "Access-Control-Allow-Origin": "*",
        }
      })
      .then(response => {
        this.getList();
      })
      .catch(err => {
        console.log(err)
      });
    }
  },
};
</script>


<style>
</style>
