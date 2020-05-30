<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.announcements')"
      >Announcements</inertia-link>
      <span class="font-medium text-indigo-400">/</span>
      {{ form.title }}
    </h1>
    <div class="max-w-3xl overflow-hidden bg-white rounded shadow">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <file-input
            v-model="form.image"
            :errors="$page.errors.image"
            class="w-full pb-8 lg:w-full"
            type="file"
            accept="image/*"
            label="Image"
          />
          <text-input
            v-model="form.title"
            :errors="$page.errors.title"
            class="w-full pb-8 pr-6"
            label="Title"
          />
          <text-input
            v-model="form.url"
            :errors="$page.errors.url"
            class="w-full pb-8 pr-6"
            label="Url (Optional)"
          />
          <textarea-input
            v-model="form.details"
            :errors="$page.errors.details"
            class="w-full pb-8 pr-6"
            label="Details"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
          <button
            v-if="!announcement.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >Delete Announcement</button>
          <loading-button
            :loading="sending"
            class="ml-auto btn-indigo"
            type="submit"
          >Update Announcement</loading-button>
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
import TextareaInput from "@/Shared/TextareaInput";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";

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
    announcement: Object
  },
  remember: "form",
  data() {
    return {
      sending: false,
      form: {
        title: this.announcement.title,
        details: this.announcement.details,
        url: this.announcement.url,
        image: this.announcement.image
      }
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("title", this.form.title);
      data.append("details", this.form.details);
      data.append("url", this.form.url || "");
      data.append("image", this.form.image || "");
      data.append("_method", "put");
      this.$inertia
        .post(this.route("announcements.update", this.announcement.id), data)
        .then(() => (this.sending = false));
    },
    restore() {
      if (confirm("Are you sure you want to restore this announcement?")) {
        this.$inertia.put(
          this.route("announcements.restore", this.announcement.id)
        );
      }
    },
    destroy() {
      if (confirm("Are you sure you want to delete this announcement?")) {
        this.$inertia.delete(
          this.route("announcements.destroy", this.announcement.id)
        );
      }
    }
  }
};
</script>
