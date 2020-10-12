<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">{{ title }}</h1>
    <div class="overflow-x-auto bg-white rounded shadow-md">
      <div class="p-4 rounded-lg">
        <trix name="trixman" :value="value" @change="handleChange" class="rounded-lg" />
        <div class="flex items-center justify-end pt-4">
          <button :disabled="saving" class="flex items-center btn-indigo" @click="saveHTML">
            <div v-if="saving" class="mr-2 btn-spinner" />Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import LoadingButton from "@/Shared/LoadingButton";
import Trix from "@/Shared/Trix";

export default {
  metaInfo() {
    return {
      title: this.title,
    };
  },
  layout: Layout,
  components: {
    Trix,
    LoadingButton,
  },
  props: {
    html: String,
    title: String,
    field: String,
  },
  data() {
    return {
      value: this.html,
      saving: false,
    };
  },
  methods: {
    saveHTML() {
      this.saving = true;
      this.$inertia.post(
        this.route("organization.save-page"),
        {
          field: this.field,
          html: this.value,
        },
        {
          onFinish: () => {
            this.saving = false;
          },
        }
      );
    },
    handleChange(value) {
      this.value = value;
    },
  },
};
</script>