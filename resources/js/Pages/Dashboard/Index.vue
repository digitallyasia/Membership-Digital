<template>
  <div class="-mr-4 lg:-mr-12">
    <div class="flex flex-col xl:flex-row">
      <div class="flex flex-col flex-wrap content-start w-full xl:w-2/3 md:flex-row">
        <div class="w-full md:w-1/2">
          <div class="mr-4 card sm:mr-4 lg:mr-12 lg:mb-12">
            <icon name="users" class="w-20 h-20" :class="'fill-indigo-400 group-hover:fill-white'" />
            <div class="flex flex-col pt-2 pl-6">
              <div class="mb-2 text-xl font-bold">Members</div>
              <p
                class="text-2xl text-center text-gray-700"
              >{{$page.auth.organization.active_members_count}}/{{$page.auth.organization.subscription.number_of_members}}</p>
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
              >{{$page.auth.organization.notifications_with_trashed_count}}/{{$page.auth.organization.subscription.number_of_notifications}}</p>
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
              <p
                class="text-2xl text-center text-gray-700"
              >{{$page.auth.organization.announcements_with_trashed_count}}/{{$page.auth.organization.subscription.number_of_announcements}}</p>
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
              >{{$page.auth.organization.benefits_with_trashed_count}}/{{$page.auth.organization.subscription.number_of_benefits}}</p>
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
              :style="{
                'background-image': `url(${card_preview ? card_preview : card ? card:'/images/card.jpg'})`,
                'background-size': 'cover',
                'background-repeat': 'no-repeat'
              }"
              class="relative object-contain w-full h-64"
            >
              <div
                v-if="$page.auth.organization.subscription && $page.auth.organization.subscription.card_customization"
                class="absolute top-0 right-0 flex items-center justify-center h-8 mt-1 mr-1"
              >
                <input ref="card" type="file" accept="image/*" class="hidden" @change="change" />
                <div
                  @click="$refs.card.click()"
                  title="Change card image"
                  class="p-1 bg-white border-2 rounded cursor-pointer"
                >
                  <icon
                    name="pencil"
                    class="block w-4 h-4 cursor-pointer fill-gray-500 hover:fill-gray-800"
                  />
                </div>
                <div
                  v-if="card_file"
                  @click="updateCard()"
                  title="Save card image"
                  class="flex flex-row p-1 ml-1 bg-white border-2 rounded cursor-pointer"
                >
                  <icon name="save" class="block w-4 h-4 cursor-pointer fill-gray-800" />
                  <span class="ml-1">Save</span>
                </div>
              </div>
              <p
                class="absolute bottom-0 left-0 px-6 pb-6 m-0 text-2xl font-semibold leading-5 text-white truncate"
                style="text-shadow: rgb(0, 0, 0,0.5) 2px 2px 2px"
              >{{ $page.auth.organization.name }}</p>
            </div>
          </div>
        </div>
        <span
          class="p-2 italic font-bold text-center text-red-600"
          v-if="card_file"
        >Please save card image</span>
        <div class="w-full mt-6 lg:mr-12 lg:w-full xl:mt-6 md:mt-0">
          <div class="relative p-0 mr-4 card sm:mr-4 lg:mr-0">
            <a
              class="absolute top-0 right-0 flex items-center justify-center h-8 px-2 mt-1 mr-1 font-semibold text-white bg-indigo-500 rounded shadow"
              :href="`/storage/qrcodes/${$page.auth.organization.qrcode}`"
              download
            >Download</a>
            <img
              :src="`/storage/qrcodes/${$page.auth.organization.qrcode}`"
              class="w-full h-full p-3"
            />
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
    Icon,
  },
  data() {
    return {
      card: this.$page.auth.organization.card_image
        ? `/storage/images/${this.$page.auth.organization.card_image}`
        : null,
      card_file: null,
      card_preview: null,
    };
  },
  methods: {
    change(e) {
      if (!e.target.files.length) return;
      let file = e.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (e) => {
        this.card_preview = e.target.result;
        this.card_file = file;
      };
    },
    updateCard() {
      var data = new FormData();
      data.append("card_image", this.card_file);
      this.$inertia
        .post(
          this.route(
            "organizations.update_card",
            this.$page.auth.organization.id
          ),
          data
        )
        .then(() => {
          this.card_file = null;
        });
    },
  },
};
</script>
