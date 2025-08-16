<template>
  <k-panel-inside>
    <k-view class="jj-place-icons">
      <k-header>Place icons</k-header>
      <ul>
        <li v-for="(icon, i) in icons" :key="i">
          <k-icon :type="icon.icon" />
          <input v-model="icon.icon" placeholder="Icon" />
          <input v-model="icon.label" placeholder="Description" />
          <k-button @click="remove(i)" icon="trash" theme="negative" size="xs" />
        </li>
      </ul>
      <k-button @click="add" icon="add">Add Icon</k-button>
      <k-button @click="save" icon="check" theme="positive">Save</k-button>
    </k-view>
  </k-panel-inside>
</template>

<script>
export default {
  props: ['headline'],
  data() {
    return { icons: [] }
  },
  created() { this.load() },
  methods: {
    async load() {
      this.icons = await this.$api.get('journey-journal/place-icons') || []
    },
    add() { this.icons.push({ icon: '', label: '' }) },
    remove(i) { this.icons.splice(i, 1) },
    async save() {
      await this.$api.post('journey-journal/place-icons', this.icons)
      this.$panel.notification.success('Saved')
    }
  }
}
</script>

<style scoped>
.jj-place-icons {
  max-width: 600px;
}
ul {
  list-style: none;
  padding: 0;
}
li {
  display: flex;
  align-items: center;
  gap: 1em;
  margin-bottom: 0.5em;
}
</style>