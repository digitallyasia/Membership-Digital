<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.benefits')"
      >Benefits</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <file-input
            v-model="form.image"
            :errors="$page.errors.image"
            class="pb-8 w-full lg:w-full"
            type="file"
            accept="image/*"
            label="Image"
          />
          <text-input
            v-model="form.title"
            :errors="$page.errors.title"
            class="pr-6 pb-8 w-full"
            label="Title"
          />
          <text-input
            v-model="form.promo_code"
            :errors="$page.errors.promo_code"
            class="pr-6 pb-8 w-full"
            label="Promo Code"
          />
          <textarea-input
            v-model="form.details"
            :errors="$page.errors.details"
            class="pr-6 pb-8 w-full"
            label="Details"
          />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
          <loading-button :loading="sending" class="btn-indigo" type="submit">Create Benefit</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import LoadingButton from "@/Shared/LoadingButton";
import SelectInput from "@/Shared/SelectInput";
import TextInput from "@/Shared/TextInput";
import FileInput from "@/Shared/FileInput";
import TextareaInput from "@/Shared/TextareaInput";

export default {
  metaInfo: { title: "Create Benefit" },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    TextareaInput
  },
  props: {},
  remember: "form",
  data() {
    return {
      sending: false,
      form: {
        title: "",
        details: "",
        promo_code: "",
        image: ""
      }
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("title", this.form.title);
      data.append("details", this.form.details);
      data.append("promo_code", this.form.promo_code);
      data.append("image", this.form.image);
      this.$inertia
        .post(this.route("benefits.store"), data)
        .then(() => (this.sending = false));
    }
  }
};
</script>
