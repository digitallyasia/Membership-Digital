<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">Members</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="w-full mt-1 form-select">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
    </div>
    <div class="pt-2 bg-white rounded-lg shadow-lg">
      <div>
        <ul class="flex border-b-4 border-gray-300" role="tablist">
          <li
            v-for="tab in tabs"
            :key="tab"
            class="px-4 py-2 bg-white"
            :class="{ 'border-b-4 border-gray-600': activeTab===tab }"
            :style="activeTab===tab ? 'margin-bottom: -4px' : ''"
          >
            <button
              v-text="tab"
              :class="{ 'font-bold': activeTab===tab }"
              class="p-1 text-xl focus:outline-none"
              role="tab"
              :aria-selected="activeTab===tab"
              @click="activeTab = tab"
            ></button>
          </li>
        </ul>
      </div>
      <div class="m-4 overflow-x-auto bg-white rounded shadow-md">
        <table class="w-full whitespace-no-wrap">
          <tr class="font-bold text-left bg-gray-300">
            <th class="px-6 pt-6 pb-4">Name</th>
            <th class="px-6 pt-6 pb-4">Email</th>
            <th class="px-6 pt-6 pb-4">Phone Number</th>
            <th class="px-6 pt-6 pb-4">Date of Birth</th>
            <th class="px-6 pt-6 pb-4" colspan="2">Address</th>
          </tr>
          <tr
            v-for="member in members.data"
            :key="member.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <span class="flex items-center px-6 py-4 focus:text-indigo-500">
                <img
                  v-if="member.profile_picture"
                  class="block w-5 h-5 mr-2 -my-2 rounded-full"
                  :src="member.profile_picture"
                />
                {{ member.name }}
              </span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.email }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.phone_number }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.dob }}</span>
            </td>
            <td class="border-t">
              <span class="flex items-center px-6 py-4" tabindex="-1">{{ member.address }}</span>
            </td>
            <td class="w-px border-t">
              <span class="flex items-center px-4" tabindex="-1">
                <button class="mr-2" tabindex="-1" title="Block Member" @click="block(member.id)">
                  <icon
                    name="block"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-gray-800"
                  />
                </button>
                <button
                  tabindex="-1"
                  type="button"
                  title="Delete Member"
                  @click="deleteMember(member.id)"
                >
                  <icon
                    name="trash"
                    class="block w-6 h-6 cursor-pointer fill-gray-500 hover:fill-red-600"
                  />
                </button>
              </span>
            </td>
          </tr>
          <tr v-if="members.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No members found.</td>
          </tr>
        </table>
      </div>
      <pagination
        :links="members.links"
        class="flex flex-wrap p-2 pt-3 mt-2 -mb-1 bg-gray-200 rounded-b-lg"
      />
    </div>
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
  metaInfo: { title: "Members" },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter
  },
  props: {
    members: Object,
    filters: Object
  },
  data() {
    return {
      activeTab: "Accepted",
      tabs: ["Accepted", "Pending", "Blocked"],
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
            "organization.members",
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
    block(id) {
      this.$inertia.post(this.route("organization.members.block"), {
        member_id: id
      });
    },
    unblock(id) {
      this.$inertia.post(this.route("organization.members.unblock"), {
        member_id: id
      });
    },
    deleteMember(id) {
      this.$inertia.post(this.route("organization.members.delete"), {
        member_id: id
      });
    }
  }
};
</script>