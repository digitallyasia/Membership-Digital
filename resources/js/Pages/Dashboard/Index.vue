<template>
  <div>
    <div class="flex flex-col xl:flex-row">
      <div class="flex flex-col flex-wrap content-start w-full xl:w-2/3 md:flex-row">
        <div class="w-full md:w-1/2">
          <div class="mr-4 card sm:mr-4 lg:mr-12 lg:mb-12">
            <icon name="users" class="w-20 h-20" :class="'fill-indigo-400 group-hover:fill-white'" />
            <div class="flex flex-col pt-2 pl-6">
              <div class="mb-2 text-xl font-bold">Members</div>
              <p class="text-2xl text-center text-gray-700">50/100</p>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/2">
          <div class="mt-6 mr-4 md:mt-0 card sm:mr-4 lg:mr-12 lg:mb-12 xl:mr-12">
            <icon
              name="notifications"
              class="w-20 h-20"
              :class="'fill-indigo-400 group-hover:fill-white'"
            />
            <div class="flex flex-col pt-2 pl-6">
              <div class="mb-2 text-xl font-bold">Notifications</div>
              <p
                class="text-2xl text-center text-gray-700"
              >{{$page.auth.organization.notifications_count}}/100</p>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/2">
          <div class="mt-6 mr-4 lg:mt-0 card sm:mr-4 lg:mr-12 lg:mb-12">
            <icon
              name="announcements"
              class="w-20 h-20"
              :class="'fill-indigo-400 group-hover:fill-white'"
            />
            <div class="flex flex-col pt-2 pl-6">
              <div class="mb-2 text-xl font-bold">Announcements</div>
              <p class="text-2xl text-center text-gray-700">50/100</p>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/2">
          <div class="mt-6 mr-4 lg:mt-0 card sm:mr-4 lg:mr-12 lg:mb-12 xl:mr-12">
            <icon
              name="benefits"
              class="w-20 h-20"
              :class="'fill-indigo-400 group-hover:fill-white'"
            />
            <div class="flex flex-col pt-2 pl-6">
              <div class="mb-2 text-xl font-bold">Benefits</div>
              <p
                class="text-2xl text-center text-gray-700"
              >{{$page.auth.organization.notifications_count}}/100</p>
            </div>
          </div>
        </div>
      </div>
      <div
        class="flex flex-col w-full mt-6 ml-auto lg:mt-0 xl:mt-0 xl:flex-col xl:w-1/3 md:flex-row xl:mr-12"
      >
        <div class="w-full lg:mr-12 lg:w-full">
          <div class="p-0 mr-4 card sm:mr-4 lg:mr-0">
            <div
              style="background-image: url('/images/card.jpg')"
              class="relative object-contain w-full h-64"
            >
              <p
                class="absolute bottom-0 left-0 px-6 pb-6 m-0 text-2xl font-semibold leading-5 truncate"
              >{{ $page.auth.organization.name }}</p>
            </div>
          </div>
        </div>
        <div class="w-full mt-6 lg:mr-12 lg:w-full xl:mt-6 md:mt-0">
          <div class="p-0 mr-4 card sm:mr-4 lg:mr-0">
            <img :src="$page.auth.organization.qrcode" class="w-full h-full" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import Icon from "@/Shared/Icon";

export default {
  metaInfo: { title: "Dashboard" },
  layout: Layout,
  components: {
    Icon
  }
};
</script>
