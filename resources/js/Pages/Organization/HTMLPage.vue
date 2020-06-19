<template>
  <div>
    <h1 class="mb-8 text-3xl font-bold">{{ title }}</h1>
    <div class="overflow-x-auto bg-white rounded shadow-md">
      <div class="p-4 rounded-lg">
        <trix
          name="trixman"
          :value="value"
          @change="handleChange"
          @file-add="handleFileAdd"
          @file-remove="handleFileRemove"
          class="rounded-lg"
        />
        <div class="flex items-center justify-end pt-4">
          <button :disabled="saving" class="flex items-center btn-indigo" @click="saveHTML">
            <div v-if="saving" class="mr-2 btn-spinner" />Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import LoadingButton from "@/Shared/LoadingButton";
import Trix from "@/Shared/Trix";

export default {
  metaInfo() {
    return {
      title: this.title
    };
  },
  layout: Layout,
  components: {
    Trix,
    LoadingButton
  },
  props: {
    html: String,
    title: String,
    field: String
  },
  data() {
    return {
      value: this.html,
      saving: false
    };
  },
  beforeDestroy() {
    this.cleanUp();
  },

  methods: {
    saveHTML() {
      this.saving = true;
      this.$inertia
        .post(this.route("organization.save-page"), {
          field: this.field,
          html: this.value
        })
        .then(() => (this.saving = false));
    },
    handleChange(value) {
      this.value = value;
    },
    handleFileAdd({ attachment }) {
      if (attachment.file) {
        this.uploadAttachment(attachment);
      }
    },
    uploadAttachment(attachment) {
      const data = new FormData();
      data.append("Content-Type", attachment.file.type);
      data.append("attachment", attachment.file);
      data.append("draftId", this.draftId);

      Nova.request()
        .post(
          `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}`,
          data,
          {
            onUploadProgress: function(progressEvent) {
              attachment.setUploadProgress(
                Math.round((progressEvent.loaded * 100) / progressEvent.total)
              );
            }
          }
        )
        .then(({ data: { url } }) => {
          return attachment.setAttributes({
            url: url,
            href: url
          });
        });
    },

    /**
     * Remove an attachment from the server
     */
    handleFileRemove({ attachment: { attachment } }) {
      Nova.request()
        .delete(
          `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}`,
          {
            params: {
              attachmentUrl: attachment.attributes.values.url
            }
          }
        )
        .then(response => {})
        .catch(error => {});
    },

    /**
     * Purge pending attachments for the draft
     */
    cleanUp() {
      if (this.field.withFiles) {
        Nova.request()
          .delete(
            `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}/${this.draftId}`
          )
          .then(response => console.log(response))
          .catch(error => {});
      }
    }
  }
};
</script>