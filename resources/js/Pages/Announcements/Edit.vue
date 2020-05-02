<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.announcements')"
      >Announcements</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.title }}
    </h1>
    <trashed-message
      v-if="announcement.deleted_at"
      class="mb-6"
      @restore="restore"
    >This announcement has been deleted.</trashed-message>
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
            v-model="form.url"
            :errors="$page.errors.url"
            class="pr-6 pb-8 w-full"
            label="Url"
          />
          <textarea-input
            v-model="form.details"
            :errors="$page.errors.details"
            class="pr-6 pb-8 w-full"
            label="Details"
          />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <button
            v-if="!announcement.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >Delete Announcement</button>
          <loading-button
            :loading="sending"
            class="btn-indigo ml-auto"
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
