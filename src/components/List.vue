<template>
    <b-card title="">
      <template #header>
        <div class="d-flex justify-content-between">
            <h6 class="mb-0">{{title}}</h6>
            <span>{{total}} items</span>
        </div>
      </template>
      <b-card-text>
        <ul class="list-items">
            <li v-for="item in items" :key="item.id">
                {{item.title}}
            </li>
        </ul>
      </b-card-text>
    </b-card>
</template>
<script>
export default {
  components: {
  },
  props: {
    title :{ required: true, type: String },
    total :{ required: false, type: Number },
    items :{ required: false, type: Array }
  },
  data() {
    return {
      isEditing: false
    };
  },
  computed: {
    isDone() {
      return this.done;
    }
  },
  methods: {
  }
}
</script>
<style>
.list-items{
    list-style: none;
    padding: 0;
    margin: 0;
}
.list-items li{
    background-color: lightgray;
    margin-bottom: 10px;
    padding: 5px;
}
</style>