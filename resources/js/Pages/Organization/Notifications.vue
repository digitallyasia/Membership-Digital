<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">Push Notifications</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
      <inertia-link
        class="btn-indigo"
        :href="route('notifications.create')"
        v-if="$page.auth.organization.subscription.number_of_notifications - $page.auth.organization.notifications_with_trashed_count > 0"
      >
        <span>Create</span>
        <span class="hidden md:inline">Notification</span>
      </inertia-link>
      <span v-else>You have reached your subscription limit</span>
    </div>
    <div class="overflow-x-auto bg-white rounded shadow-md">
      <table class="w-full whitespace-no-wrap">
        <tr class="font-bold text-left bg-gray-300">
          <th class="px-6 py-4">Title</th>
          <th class="px-6 py-4" colspan="2">Body</th>
        </tr>
        <tr
          v-for="notification in notifications.data"
          :key="notification.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <span class="flex items-center px-6 py-4" tabindex="-1">{{ notification.title }}</span>
          </td>
          <td class="border-t">
            <span class="flex items-center px-6 py-4" tabindex="-1">{{ notification.body }}</span>
          </td>
          <td class="w-px border-t">
            <span class="flex items-center px-4" tabindex="-1">
              <button
                v-if="!notification.deleted_at"
                tabindex="-1"
                type="button"
                @click="destroy(notification.id)"
              >
                <icon
                  name="trash"
                  class="block w-6 h-6 cursor-pointer fill-gray-400 hover:fill-red-600"
                />
              </button>
            </span>
          </td>
        </tr>
        <tr v-if="notifications.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No notification found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="notifications.links" />
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

export default {
  metaInfo: { title: "Push Notifications" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter
  },
  props: {
    notifications: Object,
    filters: Object
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed
      }
    };
  },
  watch: {
    form: {
      handler: debounce(function() {
        let query = pickBy(this.form);
        this.$inertia.replace(
          this.route(
            "organization.notifications",
            Object.keys(query).length ? query : { remember: "forget" }
          )
        );
      }, 400),
      deep: true
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    destroy(id) {
      if (confirm("Are you sure you want to delete this notification?")) {
        this.$inertia.delete(this.route("notifications.destroy", id));
      }
    }
  }
};
</script>