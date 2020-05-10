<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center py-3 group" :href="route('organization.dashboard')">
        <icon
          name="dashboard"
          class="w-4 h-4 mr-2"
          :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center py-3 group" :href="route('organization.profile')">
        <icon
          name="users"
          class="w-4 h-4 mr-2"
          :class="isUrl('profile') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('profile') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Profile</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center py-3 group" :href="route('organization.members')">
        <icon
          name="users"
          class="w-4 h-4 mr-2"
          :class="isUrl('members') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('members') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Members</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link
        class="flex items-center py-3 group"
        :href="route('organization.notifications')"
      >
        <icon
          name="notifications"
          class="w-4 h-4 mr-2"
          :class="isUrl('notifications') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('notifications') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Push Notifications</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link
        class="flex items-center py-3 group"
        :href="route('organization.announcements')"
      >
        <icon
          name="announcements"
          class="w-4 h-4 mr-2"
          :class="isUrl('announcements') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('announcements') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Announcements</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center py-3 group" :href="route('organization.benefits')">
        <icon
          name="printer"
          class="w-4 h-4 mr-2"
          :class="isUrl('benefits') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('benefits') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Benefits</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center py-3 group" :href="route('organization.billing')">
        <icon
          name="printer"
          class="w-4 h-4 mr-2"
          :class="isUrl('billing') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div
          :class="isUrl('billing') ? 'text-white' : 'text-indigo-300 group-hover:text-white'"
        >Billing</div>
      </inertia-link>
    </div>
  </div>
</template>

<script>
import Icon from "@/Shared/Icon";

export default {
  components: {
    Icon
  },
  props: {
    url: String
  },
  methods: {
    isUrl(...urls) {
      if (urls[0] === "") {
        return this.url === "";
      }

      return urls.filter(url => this.url.startsWith(url)).length;
    }
  }
};
</script>
