<template>
  <div>
    <label
      v-if="label"
      class="font-bold form-label"
      :for="id"
    >
      <span v-if="label">
        {{ label }}:
        <span
          v-if="!required"
          class="font-normal text-gray-500"
        >(optional)</span>
        <span
          v-else
          class="leading-4 text-red-600 align-bottom"
        >*</span>
      </span>
    </label>
    <textarea
      :id="id"
      ref="input"
      v-bind="$attrs"
      :required="required"
      class="form-textarea"
      :class="{ error: errors.length }"
      :value="value"
      @input="$emit('input', $event.target.value)"
    />
    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `textarea-input-${this._uid}`;
      }
    },
    value: String,
    label: String,
    required: {
      type: Boolean,
      default: true
    },
    errors: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    },
    select() {
      this.$refs.input.select();
    }
  }
};
</script>
