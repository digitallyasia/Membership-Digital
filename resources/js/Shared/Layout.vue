<template>
  <div>
    <portal-target name="dropdown" slim />
    <div class="flex flex-col">
      <div class="flex flex-col h-screen" @click="hideDropdownMenus">
        <div class="flex-shrink-0 lg:flex">
          <div
            class="flex items-center justify-between px-6 py-4 bg-indigo-900 lg:flex-shrink-0 lg:w-64 lg:justify-center"
          >
            <inertia-link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </inertia-link>
            <dropdown class="lg:hidden" placement="bottom-end">
              <svg
                class="w-6 h-6 fill-white"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
              </svg>
              <div slot="dropdown" class="px-8 py-4 mt-2 bg-indigo-800 rounded shadow-lg">
                <main-menu :url="url()" />
              </div>
            </dropdown>
          </div>
          <div
            class="flex items-center justify-between w-full p-4 text-sm bg-white border-b lg:py-0 lg:px-12 lg:text-lg"
          >
            <div class="mt-1 mr-4">{{ $page.auth.organization.name }}</div>
            <dropdown class="mt-1" placement="bottom-end">
              <div class="flex items-center cursor-pointer select-none group">
                <div
                  class="mr-1 text-gray-700 whitespace-no-wrap group-hover:text-indigo-600 focus:text-indigo-600"
                >
                  <span>{{ $page.auth.organization.name }}</span>
                </div>
                <icon
                  class="w-5 h-5 group-hover:fill-indigo-600 fill-gray-700 focus:fill-indigo-600"
                  name="cheveron-down"
                />
              </div>
              <div slot="dropdown" class="py-2 mt-2 text-sm bg-white rounded shadow-xl">
                <!-- <inertia-link class="block px-6 py-2 hover:bg-indigo-500 hover:text-white" :href="route('users.edit', $page.auth.organization.id)">My Profile</inertia-link> -->
                <inertia-link
                  class="block px-6 py-2 hover:bg-indigo-500 hover:text-white"
                  :href="route('organization.logout')"
                  method="post"
                >Logout</inertia-link>
              </div>
            </dropdown>
          </div>
        </div>
        <div class="flex flex-grow overflow-hidden">
          <main-menu
            :url="url()"
            class="flex-shrink-0 hidden w-64 p-6 pt-4 overflow-y-auto bg-indigo-800 lg:block"
          />
          <div class="flex-1 px-4 py-8 overflow-y-auto lg:p-12" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Dropdown from "@/Shared/Dropdown";
import FlashMessages from "@/Shared/FlashMessages";
import Icon from "@/Shared/Icon";
import Logo from "@/Shared/Logo";
import MainMenu from "@/Shared/MainMenu";

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Logo,
    MainMenu
  },
  data() {
    return {
      showUserMenu: false,
      accounts: null
    };
  },
  methods: {
    url() {
      return location.pathname.substr(1);
    },
    hideDropdownMenus() {
      this.showUserMenu = false;
    }
  }
};
</script>
