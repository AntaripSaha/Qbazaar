<template>
  <div class="card">
    <div class="card-header">
      <h4 class="text-center">{{ $lang["Delivery Timing"] }}</h4>
    </div>
    <div class="card-body">
      <form @submit.prevent="saveTiming" autocomplete="off">
        <div class="form-label-group mb-3">
          <label for="order_instructions">{{
            $lang["Order Instructions"]
          }}</label>
          <textarea
            id="order_instructions"
            v-bind:class="
              errors && errors.order_instructions
                ? 'form-control is-invalid'
                : 'form-control'
            "
            v-model="order_instructions"
            :placeholder="$lang['Order Instructions']"
          ></textarea>
          <span class="text-danger" v-if="errors && errors.order_instructions">
            {{ errors.order_instructions[0] }}
          </span>
        </div>

        <div class="form-label-group mb-3">
          <label for="delivery_type">{{ $lang["Delivery Type"] }} *</label>
          <select
            id="delivery_type"
            v-bind:class="
              errors && errors.phone
                ? 'form-control is-invalid'
                : 'form-control'
            "
            v-model="delivery_type"
          >
            <option value="express">{{ $lang["Express Delivery"] }}</option>
            <option value="preorder">{{ $lang["Pre-Order"] }}</option>
          </select>
          <span class="text-danger" v-if="errors && errors.delivery_type">
            {{ errors.delivery_type[0] }}
          </span>
        </div>

        <div v-if="delivery_type == 'preorder'" class="form-label-group mb-3">
          <label for="delivery_type">{{ $lang["Delivery Date"] }} *</label>
          <datetime
            v-model="delivery_date"
            type="datetime"
            :min-datetime="minDatetime"
            input-class="form-control"
          ></datetime>
        </div>

        <button type="submit" class="btn btn-primary mt-2">
          {{ $lang["Next: Payment Methods"] }}
        </button>
        <button
          type="button"
          @click="previousStep"
          class="btn btn-light text-dark mt-2 float-right"
        >
          {{ $lang["Back"] }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { Datetime } from "vue-datetime";
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";

export default {
  data() {
    return {
      order_instructions: "",
      delivery_type: "express",
      delivery_date: new Date().toISOString(),
      minDatetime: new Date().toISOString(),
    };
  },
  components: {
    datetime: Datetime,
  },
  computed: {
    ...mapGetters("checkout", ["errors", "delivery_details"]),
  },
  methods: {
    ...mapActions("checkout", ["storeTiming", "nextStep", "previousStep"]),
    saveTiming() {
      let data = {
        order_instructions: this.order_instructions,
        delivery_type: this.delivery_type,
        delivery_date: moment(this.delivery_date).format("YYYY-MM-DD HH:mm:ss"),
      };
      this.storeTiming(data).then(() => {
        this.nextStep();
      });
    },
  },
  created() {
    this.order_instructions = this.delivery_details.deliveryTiming
      ? this.delivery_details.deliveryTiming.order_instructions
      : "";
    this.delivery_type = this.delivery_details.deliveryTiming
      ? this.delivery_details.deliveryTiming.delivery_type
      : "express";
    this.delivery_date = this.delivery_details.deliveryTiming
      ? this.delivery_details.deliveryTiming.delivery_date
      : new Date().toISOString();
  },
};
</script>
