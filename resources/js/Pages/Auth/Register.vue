<template>
  <div
    class="flex items-center justify-center min-h-screen p-4 bg-indigo-800 sm:p-8"
  >
    <div class="w-full max-w-xl">
      <logo
        class="block w-full max-w-xs mx-auto mb-4 fill-white sm:mb-8"
        height="50"
      />
      <form
        class="overflow-hidden bg-white rounded-lg shadow-xl"
        @submit.prevent="submit"
      >
        <div class="flex flex-wrap p-4 -mb-8 sm:p-8">
          <file-input
            v-model="form.logo"
            class="w-full pb-4 lg:w-full"
            type="file"
            accept="image/*"
            label="Logo"
          />
          <div v-if="errors.logo" class="w-full pb-4 error">
            {{ errors.logo[0] }}
          </div>
          <text-input
            v-model="form.name"
            :errors="$page.props.errors.name"
            class="w-full pb-4"
            label="Organization Name"
            placeholder="Organization Name"
          />
          <div v-if="errors.name" class="w-full pb-4 error">
            {{ errors.name[0] }}
          </div>
          <text-input
            v-model="form.email"
            :errors="$page.props.errors.email"
            class="w-full pb-4 pr-0"
            label="Main Admin Email"
            type="email"
            placeholder="Main Admin Email"
          />
          <div v-if="errors.email" class="w-full pb-4 error">
            {{ errors.email[0] }}
          </div>
          <!-- <text-input
            v-model="form.unique_id"
            :errors="$page.props.errors.unique_id"
            class="w-1/2 pb-4"
            label="Unique ID"
            placeholder="Unique ID"
          /> -->

          <text-input
            v-model="form.password"
            class="w-full pb-4 pr-0 sm:pr-6 sm:w-1/2"
            label="Password"
            placeholder="Password"
            type="password"
            :errors="$page.props.errors.password"
          />
          <div v-if="errors.password" class="w-full pb-4 error">
            {{ errors.password[0] }}
          </div>

          <text-input
            v-model="form.password_confirmation"
            class="w-full pb-4 sm:w-1/2"
            label="Confirm Password"
            placeholder="Confirm Password"
            type="password"
          />
          <div class="w-full pb-4">
            <label for="auto_join" class="inline-block w-full mb-6 font-bold"
              >Would you like to add your members manually or
              automatically?</label
            >
            <div class="flex flex-row justify-around">
              <div class="w-1/4">
                <div class="relative">
                  <label class="flex items-center font-bold">
                    <input
                      class="mr-2"
                      name="auto_join"
                      type="radio"
                      required
                      v-model="form.auto_join"
                      value="1"
                    />
                    Automatic
                  </label>
                </div>
              </div>
              <div class="w-1/4">
                <div class="relative">
                  <label class="flex items-center font-bold">
                    <input
                      class="mr-2"
                      name="auto_join"
                      type="radio"
                      required
                      v-model="form.auto_join"
                      value="0"
                    />
                    Manual
                  </label>
                </div>
              </div>
            </div>
            <div v-if="errors.auto_join" class="w-full pb-4 error">
              {{ errors.auto_join[0] }}
            </div>
          </div>
          <!-- <text-input
            v-model="form.address"
            :errors="$page.props.errors.address"
            class="w-full pb-4"
            label="Address"
            placeholder="Address"
          /> -->
          <text-input
            v-model="form.city"
            :errors="$page.props.errors.city"
            class="w-full pb-4 pr-0 sm:pr-6 sm:w-1/3"
            label="City"
            placeholder="City"
          />
          <div v-if="errors.city" class="w-full pb-4 error">
            {{ errors.city[0] }}
          </div>
          <text-input
            v-model="form.state"
            :errors="$page.props.errors.state"
            class="w-full pb-4 pr-0 sm:pr-6 sm:w-1/3"
            label="State"
            placeholder="State"
          />
          <div v-if="errors.state" class="w-full pb-4 error">
            {{ errors.state[0] }}
          </div>
          <text-input
            v-model="form.postal_code"
            :errors="$page.props.errors.postal_code"
            class="w-full pb-4 sm:w-1/3"
            label="Postal Code"
            placeholder="Postal Code"
          />
          <div v-if="errors.postal_code" class="w-full pb-4 error">
            {{ errors.postal_code[0] }}
          </div>
          <text-input
            v-model="form.phone"
            :errors="$page.props.errors.phone"
            class="w-full pb-4"
            label="Phone"
            placeholder="+60171234567"
          />
          <div v-if="errors.phone" class="w-full pb-4 error">
            {{ errors.phone[0] }}
          </div>
          <textarea-input
            v-model="form.description"
            :errors="$page.props.errors.description"
            class="w-full pb-4"
            label="Description"
            placeholder="Let your members know what your organisation is about"
          />
          <div v-if="errors.description" class="w-full pb-4 error">
            {{ errors.description[0] }}
          </div>
          <label class="lblReferredBy">Referred By:</label>
          <input
            placeholder="8452683848"
            type="number"
            class="referredby"
            v-model="form.referred_by"
          />
          <div class="w-full pb-4 error">{{ refferByErrorMsg }}</div>
          <!-- <textarea-input
            v-model="form.referred_by"
           
            class="w-full pb-4"
            label="Referred by"
            placeholder="Organization referred by"
          /> -->
          <loading-button
            :loading="sending"
            class="justify-center w-full mb-8 text-2xl font-bold text-center btn-indigo"
            type="submit"
            >Register free account</loading-button
          >
        </div>
        <div
          class="flex items-center justify-center p-4 bg-gray-100 border-t border-gray-200"
        >
          <inertia-link
            class="font-bold hover:underline"
            :href="route('organization.login')"
            >Already a registered Organisation? Login</inertia-link
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Logo from "@/Shared/Logo";
import axios from "axios";

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
    // errors: Object,
  },
  data() {
    return {
      sending: false,
      refferByErrorMsg: "",
      form: {
        logo: "",
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        phone: "",
        city: "",
        state: "",
        postal_code: "",
        description: "",
        referred_by: "",
        auto_join: "",
      },
      errors: [],
      success: false,
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

      data.append("phone", this.form.phone);
      data.append("city", this.form.city);
      data.append("state", this.form.state);
      data.append("postal_code", this.form.postal_code);

      data.append("description", this.form.description);
      data.append("referred_by", this.form.referred_by);
      data.append("auto_join", this.form.auto_join);
      // this.$inertia.post(this.route("organization.register"), data, {
      //   onFinish: (response) => {
      //     console.log(response)
      //     this.sending = false;
      //   },

      // });
      axios
        .post("register", data)
        .then((response) => {
          this.errors = [];
          this.refferByErrorMsg = response.data;
          this.sending = false;
          if (response.data == "") {
            location.reload();
          }
          console.log(response.data);
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
          this.sending = false;
          this.errors = error.response.data.errors;
          console.log(error.response.data);
        });
    },
  },
};
</script>

