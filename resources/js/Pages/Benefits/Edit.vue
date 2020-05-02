<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.benefits')"
      >Benefits</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message
      v-if="benefit.deleted_at"
      class="mb-6"
      @restore="restore"
    >This benefit has been deleted.</trashed-message>
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
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <loading-button :loading="sending" class="btn-indigo ml-auto" type="submit">Update Benefit</loading-button>
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
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
import TextareaInput from "@/Shared/TextareaInput";

export default {
  metaInfo() {
    return {
      title: `${this.form.title}`
    };
  },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    TextareaInput
  },
  props: {
    benefit: Object
  },
  remember: "form",
  data() {
    return {
      sending: false,
      form: {
        title: this.benefit.title,
        details: this.benefit.details,
        promo_code: this.benefit.promo_code,
        image: this.benefit.image
      }
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("title", this.form.title);
      data.append("details", this.form.details);
      data.append("promo_code", this.form.promo_code || "");
      data.append("image", this.form.image || "");
      data.append("_method", "put");
      this.$inertia
        .post(this.route("benefits.update", this.benefit.id), data)
        .then(() => (this.sending = false));
    },
    restore() {
      if (confirm("Are you sure you want to restore this benefit?")) {
        this.$inertia.put(this.route("benefits.restore", this.benefit.id));
      }
    }
  }
};
</script>