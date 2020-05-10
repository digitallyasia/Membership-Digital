<template>
  <div>
    <!-- <label v-if="label" class="form-label">{{ label }}:</label> -->
    <div
      class="flex flex-col w-auto max-w-md p-0 mx-auto border-0 form-input"
      :class="{ error: errors.length }"
    >
      <img
        class="object-cover rounded-lg shadow-md"
        :src="typeof value === 'string'&& value!==''?value:preview ? preview : 'https://via.placeholder.com/300x300'"
      />
      <input
        ref="file"
        type="file"
        width="200"
        height="200"
        :accept="accept"
        class="hidden"
        @change="change"
      />
      <div v-if="!value" class="absolute bottom-0 p-2">
        <button
          type="button"
          class="px-4 py-1 text-xs font-medium text-white bg-gray-500 rounded-sm hover:bg-gray-700"
          @click="browse"
        >Browse</button>
      </div>
      <div v-else class="absolute bottom-0 flex items-center justify-between w-full p-2">
        <div class="flex-1 pr-1 mr-auto">
          {{ value.name }}
          <span class="text-xs text-gray-500">({{ filesize(value.size) }})</span>
        </div>
        <button
          type="button"
          class="px-4 py-1 ml-auto text-xs font-medium text-white bg-gray-500 rounded-sm hover:bg-gray-700"
          @click="remove"
        >Remove</button>
      </div>
    </div>
    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  props: {
    value: [File, String],
    label: String,
    accept: String,
    errors: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      preview: null
    };
  },
  watch: {
    value(value) {
      if (!value) {
        this.$refs.file.value = "";
      }
    }
  },
  methods: {
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024));
      return (
        (size / Math.pow(1024, i)).toFixed(2) * 1 +
        " " +
        ["B", "kB", "MB", "GB", "TB"][i]
      );
    },
    browse() {
      this.$refs.file.click();
    },
    change(e) {
      if (!e.target.files.length) return;

      let file = e.target.files[0];

      let reader = new FileReader();

      reader.readAsDataURL(file);

      reader.onload = e => {
        this.preview = e.target.result;
        this.$emit("input", file);
      };
    },
    remove() {
      this.preview = null;
      this.$emit("input", null);
    }
  }
};
</script>
