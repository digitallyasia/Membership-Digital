<template>
  <div class="flex flex-col items-center justify-center w-full min-h-screen bg-indigo-900">
    <Logo class="block w-full max-w-xs mx-auto mb-4 text-white fill-current" height="{50}" />
    <div class="container mx-auto">
      <div class="flex flex-wrap justify-center">
        <div class="w-full max-w-sm">
          <div
            v-if="$page.status"
            class="px-3 py-4 mb-4 text-sm text-green-700 bg-green-100 border border-t-8 border-green-600 rounded"
            role="alert"
          >{{ $page.status }}</div>

          <div class="flex flex-col break-words bg-white border-2 rounded shadow-md">
            <div class="px-6 py-3 mb-0 font-semibold text-gray-700 bg-gray-200">Reset Password</div>

            <div class="w-full p-6">
              <TextInput
                class="mt-5"
                label="Email"
                name="email"
                type="email"
                :errors="errors.email"
                autocomplete="email"
                v-model="email"
              />

              <div class="flex flex-wrap mt-4">
                <loading-button
                  :loading="sending"
                  class="justify-center w-full text-center btn-indigo"
                  @click="send"
                >Send Password Reset Link</loading-button>

                <p class="w-full mt-8 -mb-4 text-xs text-center text-gray-700">
                  <a
                    class="text-blue-500 no-underline hover:text-blue-700"
                    :href="route('organization.login')"
                  >Back to login</a>
                </p>
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
  },
  data() {
    return {
      sending: false,
      email: "",
    };
  },
  methods: {
    send() {
      this.sending = true;
      this.$inertia
        .post(this.route("organization.password.email"), { email: this.email })
        .then(() => {
          this.sending = false;
        })
        .catch(() => {
          this.sending = false;
        });
    },
  },
};
</script>
