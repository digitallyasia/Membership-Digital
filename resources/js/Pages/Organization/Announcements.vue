<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">Announcements</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset"></search-filter>
      <inertia-link
        class="btn-indigo"
        :href="route('announcements.create')"
        v-if="$page.auth.organization.subscription.number_of_announcements - $page.auth.organization.announcements_with_trashed_count > 0"
      >
        <span>Create</span>
        <span class="hidden md:inline">Announcement</span>
      </inertia-link>
      <span v-else>You have reached your subscription limit</span>
    </div>
    <div class="overflow-x-auto bg-white rounded shadow-md">
      <table class="w-full whitespace-no-wrap">
        <tr class="font-bold text-left bg-gray-300">
          <th class="px-6 py-4">Title</th>
          <th class="px-6 py-4">Details</th>
          <th class="px-6 py-4">URL</th>
          <th class="px-6 py-4" colspan="2">Created At</th>
        </tr>
        <tr
          v-for="announcement in announcements.data"
          :key="announcement.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('announcements.edit',announcement.id)"
            >
              <img
                v-if="announcement.image"
                class="block w-5 h-5 mr-2 -my-2 rounded-full"
                :src="announcement.image"
              />
              {{ announcement.title }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('announcements.edit',announcement.id)"
            >{{ announcement.details }}</inertia-link>
          </td>
          <td class="border-t">
            <inertia-link
              class="flex items-center px-6 py-4"
              tabindex="-1"
              :href="route('announcements.edit',announcement.id)"
            >{{ announcement.url }}</inertia-link>
          </td>
          <td class="border-t">
            <span
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >{{ moment(announcement.created_at).format('LLL') }}</span>
          </td>
          <td class="w-px border-t">
            <span class="flex items-center px-4" tabindex="-1">
              <inertia-link
                class="mr-2"
                tabindex="-1"
                :href="route('announcements.edit',announcement.id)"
              >
                <icon
                  name="pencil"
                  class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                />
              </inertia-link>
              <button
                v-if="!announcement.deleted_at"
                tabindex="-1"
                type="button"
                @click="destroy(announcement.id)"
              >
                <icon
                  name="trash"
                  class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-red-600"
                />
              </button>
            </span>
          </td>
        </tr>
        <tr v-if="announcements.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No announcements found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="announcements.links" />
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
  metaInfo: { title: "Announcements" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter
  },
  props: {
    announcements: Object,
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
            "organization.announcements",
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
      if (confirm("Are you sure you want to delete this announcement?")) {
        this.$inertia.delete(this.route("announcements.destroy", id));
      }
    }
  }
};
</script>