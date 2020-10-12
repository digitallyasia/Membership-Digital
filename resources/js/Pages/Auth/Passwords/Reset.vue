<template>
  <div class="flex flex-col items-center justify-center w-full min-h-screen bg-indigo-900">
    <Logo class="block w-full max-w-xs mx-auto mb-4 text-white fill-current" height="{50}" />
    <div class="container mx-auto">
      <div class="flex flex-wrap justify-center">
        <div class="w-full max-w-sm">
          <div class="flex flex-col break-words bg-white border-2 rounded shadow-md">
            <div class="px-6 py-3 mb-0 font-semibold text-gray-700 bg-gray-200">Reset Password</div>
            <div class="w-full p-6">
              <TextInput
                class="mb-6"
                label="E-Mail Address"
                name="email"
                type="email"
                :errors="errors.email"
                v-model="email"
                autocomplete="email"
                required
                readonly
              />

              <TextInput
                class="mb-6"
                label="Password"
                name="password"
                type="password"
                :errors="errors.password"
                autocomplete="new-password"
                v-model="password"
              />

              <TextInput
                class="mb-6"
                label="Confirm Password"
                name="password_confirmation"
                type="password"
                :errors="errors.password_confirmation"
                autocomplete="new-password"
                v-model="password_confirmation"
              />

              <div class="flex flex-wrap">
                <loading-button
                  :loading="sending"
                  class="justify-center w-full text-center btn-indigo"
                  @click="reset"
                >Reset Password</loading-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingButton from "@/Shared/LoadingButton";
import Logo from "@/Shared/Logo";
import TextInput from "@/Shared/TextInput";
export default {
  metaInfo: { title: "Login" },
  components: {
    LoadingButton,
    Logo,
    TextInput,
  },
  props: {
    errors: Object,
    email: String,
    token: String,
  },
  data() {
    return {
      sending: false,
      password: "",
      password_confirmation: "",
    };
  },
  methods: {
    reset() {
      this.sending = true;
      this.$inertia.post(
        this.route("organization.password.update"),
        {
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          token: this.token,
        },
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
