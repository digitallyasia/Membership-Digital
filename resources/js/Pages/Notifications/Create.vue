<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link
        class="text-indigo-400 hover:text-indigo-600"
        :href="route('organization.notifications')"
      >Notifications</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input
            v-model="form.title"
            :errors="$page.props.errors.title"
            class="pr-6 pb-8 w-full"
            label="Title"
          />
          <textarea-input
            v-model="form.body"
            :errors="$page.props.errors.body"
            class="pr-6 pb-8 w-full"
            label="Body"
          />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
          <loading-button :loading="sending" class="btn-indigo" type="submit">Create Notification</loading-button>
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
  metaInfo: { title: "Create Notification" },
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
        title: null,
        body: null,
      },
    };
  },
  methods: {
    submit() {
      this.sending = true;
      this.$inertia.post(this.route("notifications.store"), this.form, {
        onFinish: () => {
          this.sending = false;
        },
      });
    },
  },
};
</script>
