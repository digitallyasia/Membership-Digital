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
    <input
      :id="id"
      ref="input"
      v-bind="$attrs"
      class="px-3 rounded-lg form-input"
      :class="{ error: errors.length }"
      :type="type"
      :required="required"
      :value="value"
      @input="$emit('input', $event.target.value)"
    />
    <div
      v-if="errors.length"
      class="form-error"
    >{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default () {
        return `text-input-${this._uid}`;
      }
    },
    type: {
      type: String,
      default: "text"
    },
    required: {
      type: Boolean,
      default: true
    },
    readonly: {
      type: Boolean,
      default: false
    },
    value: String,
    label: String,

    errors: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    focus () {
      this.$refs.input.focus();
    },
    select () {
      this.$refs.input.select();
    },
    setSelectionRange (start, end) {
      this.$refs.input.setSelectionRange(start, end);
    }
  }
};
</script>
