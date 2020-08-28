<template>
  <div class="flex items-center justify-center min-h-screen p-6 bg-indigo-800">
    <div class="w-full max-w-md">
      <logo class="block w-full max-w-xs mx-auto fill-white" height="50" />
      <form class="mt-8 overflow-hidden bg-white rounded-lg shadow-xl" @submit.prevent="submit">
        <div class="px-10 py-10">
          <h1 class="text-3xl font-bold text-center">Welcome Back!</h1>
          <div class="w-24 mx-auto mt-6 border-b-2" />
          <text-input
            v-model="form.email"
            :errors="$page.errors.email"
            class="mt-10"
            label="Email"
            type="email"
            autofocus
            autocapitalize="off"
          />
          <text-input v-model="form.password" class="mt-6" label="Password" type="password" />
          <div class="flex items-center justify-between mt-6 mb-8">
            <label class="flex items-center select-none" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
              <span class="text-sm">Remember Me</span>
            </label>
            <inertia-link
              class="font-bold hover:underline"
              :href="route('organization.password.request')"
            >Forget password?</inertia-link>
          </div>
          <loading-button
            :loading="sending"
            class="justify-center w-full text-2xl font-bold text-center btn-indigo"
            type="submit"
          >Login</loading-button>
        </div>
        <div
          class="flex items-center justify-between px-10 py-4 bg-gray-100 border-t border-gray-200"
        >
          <inertia-link
            class="font-bold hover:underline"
            :href="route('organization.register')"
          >Not a registered Organisation? Register here!</inertia-link>
        </div>
      </form>
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
      form: {
        email: "",
        password: "",
        remember: null,
      },
    };
  },
  methods: {
    submit() {
      this.sending = true;
      this.$inertia
        .post(this.route("organization.login"), {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember,
        })
        .then(() => (this.sending = false));
    },
  },
};
</script>
