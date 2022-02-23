<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">
      <span class="text-indigo-400 hover:text-indigo-600"
        >Organization Profile</span
      >
    </h1>

    <div class="max-w-4xl overflow-hidden bg-white rounded-lg shadow">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <file-input
            v-model="form.logo"
            :errors="$page.props.errors.logo"
            class="w-full pb-8 lg:w-full"
            type="file"
            :rounded="false"
            accept="image/*"
            label="Logo"
          />
          <text-input
            v-model="form.name"
            :errors="$page.props.errors.name"
            class="w-full pb-8 pr-6"
            label="Organization Name"
          />
          <text-input
            v-model="form.email"
            :errors="$page.props.errors.email"
            class="w-1/2 pb-8 pr-6"
            label="Email"
          />
          <text-input
            v-model="form.phone"
            :errors="$page.props.errors.phone"
            class="w-1/2 pb-8 pr-6"
            label="Phone"
          />
          <text-input
            v-model="form.city"
            :errors="$page.props.errors.city"
            class="w-1/3 pb-8 pr-6"
            label="City"
          />
          <text-input
            v-model="form.state"
            :errors="$page.props.errors.state"
            class="w-1/3 pb-8 pr-6"
            label="State"
          />
          <text-input
            v-model="form.postal_code"
            :errors="$page.props.errors.postal_code"
            class="w-1/3 pb-8 pr-6"
            label="Postal Code"
          />
          <textarea-input
            v-model="form.description"
            :errors="$page.props.errors.description"
            class="w-full pb-8 pr-6"
            label="Description"
          />
          <label class="lblReferredBy">Referral Code:</label>
          <input
            readonly
            disabled
            v-model="form.referral_code"
            class="w-full pb-8 pr-6 referredby"
          />
          <label class="lblReferredBy">Referred By:</label>
          <input
            readonly
            disabled
            v-model="form.referred_by"
            class="w-full pb-8 pr-6 referredby"
          />

          <text-input
            v-model="form.website"
            :errors="$page.props.errors.website"
            class="w-full pb-8 pr-6"
            label="Website"
            placeholder="https://www.example.com"
            :required="false"
          />
          <text-input
            v-model="form.facebook"
            :errors="$page.props.errors.facebook"
            class="w-full pb-8 pr-6"
            label="Facebook"
            placeholder="https://www.facebook.com"
            :required="false"
          />
          <text-input
            v-model="form.whatsapp"
            :errors="$page.props.errors.whatsapp"
            class="w-full pb-8 pr-6"
            label="WhatsApp"
            placeholder="https://web.whatsapp.com"
            :required="false"
          />
          <text-input
            v-model="form.instagram"
            :errors="$page.props.errors.instagram"
            class="w-full pb-8 pr-6"
            label="Instagram"
            placeholder="https://www.instagram.com"
            :required="false"
          />
          <text-input
            v-model="form.youtube"
            :errors="$page.props.errors.youtube"
            class="w-full pb-8 pr-6"
            label="Youtube"
            placeholder="https://www.youtube.com"
            :required="false"
          />
          <text-input
            v-model="form.twitter"
            :errors="$page.props.errors.twitter"
            class="w-full pb-8 pr-6"
            label="Twitter"
            placeholder="https://www.twitter.com"
            :required="false"
          />
          <text-input
            v-model="form.telegram"
            :errors="$page.props.errors.telegram"
            class="w-full pb-8 pr-6"
            label="Telegram"
            placeholder="https://www.telegram.com"
            :required="false"
          />
        </div>
        <div
          class="flex items-center justify-between px-8 py-4 bg-gray-100 border-t border-gray-200 "
        >
          <loading-button
            :loading="deleting"
            class="mr-auto btn-delete"
            type="button"
            @click="destroy"
            >Delete Organization</loading-button
          >
          <loading-button
            :loading="sending"
            class="ml-auto btn-indigo"
            type="submit"
            >Update</loading-button
          >
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
      deleting: false,
      form: JSON.parse(JSON.stringify(this.$page.props.auth.organization)),
    };
  },
  methods: {
    destroy() {
      if (confirm("Are you sure you want to delete your Organization?")) {
        this.$inertia.delete(this.route("organization.delete"));
      }
    },
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("logo", this.form.logo);
      data.append("name", this.form.name);
      data.append("email", this.form.email);
      data.append("phone", this.form.phone);
      data.append("city", this.form.city);
      data.append("state", this.form.state);
      data.append("postal_code", this.form.postal_code);
      data.append("description", this.form.description);
      data.append("website", this.form.website);
      data.append("facebook", this.form.facebook);
      data.append("instagram", this.form.instagram);
      data.append("youtube", this.form.youtube);
      data.append("whatsapp", this.form.whatsapp);
      data.append("twitter", this.form.twitter);
      data.append("telegram", this.form.telegram);
      data.append("_method", "put");
      this.$inertia.post(
        this.route("organizations.update", this.form.id),
        data,
        {
          onFinish: () => {
            this.sending = false;
          },
        }
      );
    },
  },
};
</script>
