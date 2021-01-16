<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.announcements')"
      >Announcements</inertia-link>
      <span class="font-medium text-indigo-400">/</span> Create
    </h1>
    <div class="max-w-3xl overflow-hidden bg-white rounded shadow">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <file-input
            v-model="form.image"
            :errors="$page.props.errors.image"
            class="w-full pb-8 lg:w-full"
            type="file"
            accept="image/*"
            label="Image"
          />
          <text-input
            v-model="form.title"
            :errors="$page.props.errors.title"
            class="w-full pb-8 pr-6"
            label="Title"
          />
          <text-input
            v-model="form.url"
            :errors="$page.props.errors.url"
            class="w-full pb-8 pr-6"
            label="Url"
            :required="false"
          />
          <textarea-input
            v-model="form.details"
            :errors="$page.props.errors.details"
            class="w-full pb-8 pr-6"
            label="Details"
          />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
          <loading-button :loading="sending" class="btn-indigo" type="submit">Create Announcement</loading-button>
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
  metaInfo: { title: "Create Announcement" },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    TextareaInput,
  },
  props: {},
  remember: "form",
  data() {
    return {
      sending: false,
      form: {
        title: "",
        details: "",
        url: "",
        image: "",
      },
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("title", this.form.title);
      data.append("details", this.form.details);
      data.append("url", this.form.url);
      data.append("image", this.form.image);
      this.$inertia.post(this.route("announcements.store"), data, {
        onFinish: () => {
          this.sending = false;
        },
      });
    },
  },
};
</script>
