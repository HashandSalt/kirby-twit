<template>
  <section class="k-twit-section">
    <k-field class="k-twit-text">
      <k-input type="textarea" theme="field" v-model="text">
    </k-field>
    <k-button @click="onSend" theme="positive">Send Tweet</k-button>
  </section>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
  },
  data() {
    return {
        headline: null,
        text: ""
    }
  },
  methods: {
    onInput(value) {
      this.text = value;
    },
    onSend() {
      this.$api.post('twit/send', {text: this.text});
    }
  },
  created() {

  },

};
</script>
