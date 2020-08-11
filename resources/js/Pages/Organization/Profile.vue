<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">
      <span class="text-indigo-400 hover:text-indigo-600">Organization Profile</span>
    </h1>

    <div class="max-w-4xl overflow-hidden bg-white rounded-lg shadow">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <file-input
            v-model="form.logo"
            :errors="$page.errors.logo"
            class="w-full pb-8 lg:w-full"
            type="file"
            :rounded="false"
            accept="image/*"
            label="Logo"
          />
          <text-input
            v-model="form.name"
            :errors="$page.errors.name"
            class="w-full pb-8 pr-6"
            label="Name"
          />
          <text-input
            v-model="form.email"
            :errors="$page.errors.email"
            class="w-1/2 pb-8 pr-6"
            label="Email"
          />
          <text-input
            v-model="form.phone"
            :errors="$page.errors.phone"
            class="w-1/2 pb-8 pr-6"
            label="Phone"
          />
          <text-input
            v-model="form.address"
            :errors="$page.errors.address"
            class="w-full pb-8 pr-6"
            label="Address"
          />
          <text-input
            v-model="form.city"
            :errors="$page.errors.city"
            class="w-1/3 pb-8 pr-6"
            label="City"
          />
          <text-input
            v-model="form.state"
            :errors="$page.errors.state"
            class="w-1/3 pb-8 pr-6"
            label="State"
          />
          <text-input
            v-model="form.postal_code"
            :errors="$page.errors.postal_code"
            class="w-1/3 pb-8 pr-6"
            label="Postal Code"
          />
          <textarea-input
            v-model="form.description"
            :errors="$page.errors.description"
            class="w-full pb-8 pr-6"
            label="Description"
          />
          <text-input
            v-model="form.website"
            :errors="$page.errors.website"
            class="w-full pb-8 pr-6"
            label="Website"
          />
          <text-input
            v-model="form.facebook"
            :errors="$page.errors.facebook"
            class="w-full pb-8 pr-6"
            label="Facebook"
          />
          <text-input
            v-model="form.whatsapp"
            :errors="$page.errors.whatsapp"
            class="w-full pb-8 pr-6"
            label="WhatsApp"
          />
          <text-input
            v-model="form.instagram"
            :errors="$page.errors.instagram"
            class="w-full pb-8 pr-6"
            label="Instagram"
          />
          <text-input
            v-model="form.youtube"
            :errors="$page.errors.youtube"
            class="w-full pb-8 pr-6"
            label="Youtube"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
          <loading-button :loading="sending" class="ml-auto btn-indigo" type="submit">Update</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import pickBy from "lodash/pickBy";
import SearchFilter from "@/Shared/SearchFilter";
import debounce from "lodash/debounce";
import LoadingButton from "@/Shared/LoadingButton";
import SelectInput from "@/Shared/SelectInput";
import TextInput from "@/Shared/TextInput";
import TextareaInput from "@/Shared/TextareaInput";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
export default {
  metaInfo: { title: "Members" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    TextareaInput,
  },
  props: {
    members: Object,
    filters: Object,
  },
  data() {
    return {
      sending: false,
      form: JSON.parse(JSON.stringify(this.$page.auth.organization)),
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("logo", this.form.logo);
      data.append("name", this.form.name);
      data.append("email", this.form.email);
      data.append("address", this.form.address);
      data.append("phone", this.form.phone);
      data.append("city", this.form.city);
      data.append("state", this.form.state);
      data.append("postal_code", this.form.postal_code);
      data.append("description", this.form.description);
      data.append("_method", "put");
      this.$inertia
        .post(this.route("organizations.update", this.form.id), data)
        .then(() => (this.sending = false));
    },
  },
};
</script>