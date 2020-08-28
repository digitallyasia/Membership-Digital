<template>
  <div class="flex items-center justify-center min-h-screen p-8 bg-indigo-800">
    <div class="w-full max-w-4xl">
      <logo class="block w-full max-w-xs mx-auto fill-white" height="50" />
      <form class="mt-8 overflow-hidden bg-white rounded-lg shadow-xl" @submit.prevent="submit">
        <div class="flex flex-wrap p-8 -mb-8">
          <file-input
            v-model="form.logo"
            :errors="$page.errors.logo"
            class="w-full pb-8 lg:w-full"
            type="file"
            accept="image/*"
            label="Logo"
          />
          <text-input
            v-model="form.name"
            :errors="$page.errors.name"
            class="w-full pb-8"
            label="Name"
            placeholder="Name"
          />
          <text-input
            v-model="form.email"
            :errors="$page.errors.email"
            class="w-1/2 pb-8 pr-6"
            label="Main Admin Email"
            type="email"
            placeholder="Main Admin Email"
          />
          <text-input
            v-model="form.unique_id"
            :errors="$page.errors.unique_id"
            class="w-1/2 pb-8"
            label="Unique ID"
            placeholder="Unique ID"
          />

          <text-input
            v-model="form.password"
            class="w-1/2 pb-8 pr-6"
            label="Password"
            placeholder="Password"
            type="password"
          />
          <text-input
            v-model="form.password_confirmation"
            class="w-1/2 pb-8"
            label="Confirm Password"
            placeholder="Confirm Password"
            type="password"
          />
          <div class="w-full pb-8">
            <label
              for="auto_join"
              class="inline-block w-full mb-6 font-bold"
            >Would you like to add your members manually or automatically?</label>
            <div class="flex flex-row justify-around">
              <div class="w-1/4">
                <div class="relative">
                  <label class="font-bold">
                    <input
                      name="auto_join"
                      type="radio"
                      required
                      v-model="form.auto_join"
                      value="1"
                    /> Automatic
                  </label>
                </div>
              </div>
              <div class="w-1/4">
                <div class="relative">
                  <label class="font-bold">
                    <input
                      name="auto_join"
                      type="radio"
                      required
                      v-model="form.auto_join"
                      value="0"
                    /> Manual
                  </label>
                </div>
              </div>
            </div>
            <div v-if="$page.errors.auto_join" class="form-error">{{ $page.errors.auto_join[0] }}</div>
          </div>
          <text-input
            v-model="form.address"
            :errors="$page.errors.address"
            class="w-full pb-8"
            label="Address"
            placeholder="Address"
          />
          <text-input
            v-model="form.city"
            :errors="$page.errors.city"
            class="w-1/3 pb-8 pr-6"
            label="City"
            placeholder="City"
          />
          <text-input
            v-model="form.state"
            :errors="$page.errors.state"
            class="w-1/3 pb-8 pr-6"
            label="State"
            placeholder="State"
          />
          <text-input
            v-model="form.postal_code"
            :errors="$page.errors.postal_code"
            class="w-1/3 pb-8"
            label="Postal Code"
            placeholder="Postal Code"
          />
          <text-input
            v-model="form.phone"
            :errors="$page.errors.phone"
            class="w-full pb-8"
            label="Phone"
            placeholder="Phone"
          />
          <textarea-input
            v-model="form.description"
            :errors="$page.errors.description"
            class="w-full pb-8"
            label="Description"
            placeholder="Let your members know what your organisation is about"
          />
          <loading-button
            :loading="sending"
            class="justify-center w-full mb-8 text-2xl font-bold text-center btn-indigo"
            type="submit"
          >Register free account</loading-button>
        </div>
        <div class="flex items-center justify-center p-4 bg-gray-100 border-t border-gray-200">
          <inertia-link
            class="font-bold hover:underline"
            :href="route('organization.login')"
          >Already a registered Organisation? Login</inertia-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Logo from "@/Shared/Logo";
import Icon from "@/Shared/Icon";
import mapValues from "lodash/mapValues";
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
  metaInfo: { title: "Register" },
  components: {
    Icon,
    SearchFilter,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    Logo,
    TextareaInput,
  },
  props: {
    errors: Object,
  },
  data() {
    return {
      sending: false,
      form: {
        logo: "",
        name: "",
        email: "",
        unique_id: "",
        password: "",
        password_confirmation: "",
        address: "",
        phone: "",
        city: "",
        state: "",
        postal_code: "",
        description: "",
        auto_join: "",
      },
    };
  },
  methods: {
    submit() {
      this.sending = true;
      var data = new FormData();
      data.append("logo", this.form.logo);
      data.append("name", this.form.name);
      data.append("email", this.form.email);
      data.append("password", this.form.password);
      data.append("password_confirmation", this.form.password_confirmation);
      data.append("address", this.form.address);
      data.append("phone", this.form.phone);
      data.append("unique_id", this.form.unique_id);
      data.append("city", this.form.city);
      data.append("state", this.form.state);
      data.append("postal_code", this.form.postal_code);
      data.append("description", this.form.description);
      data.append("auto_join", this.form.auto_join);
      this.$inertia
        .post(this.route("organization.register"), data)
        .then(() => (this.sending = false));
    },
  },
};
</script>
