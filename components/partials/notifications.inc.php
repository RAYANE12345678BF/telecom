

<div class="notification-dropdown" id="notificationDropdown">
    <!-- <div class="no-notifications">
        Aucune notification pour le moment.
        </div> -->
    <div class="w-full flex flex-col space-y-1" id="notifications-container">
        <!-- start a notification with two actions (accept/reject) -->
        <template x-if="notifications.length > 0">
            <template x-for="notification in notifications">
                <a
                    x-init="$el.dataset.read = notification['read_state'];$el.dataset.id = notification['id']"
                    :key="notification['id']"
                    :class="{'bg-gray-50' : notification['read_state'] != 0,'bg-gray-200' : notification['read_state'] == 0 }"
                    x-intersect="setRead($el, $data)" :href="notification['url']" class="flex flex-col space-y-2 items-center justify-between p-2  hover:bg-slate-300 duration-300 ease-in-out rounded-lg">
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" x-text="notification['title']"></p>
                            <p class="text-xs text-gray-500" x-text="notification['description']"></p>
                        </div>
                    </div>
                </a>
            </template>
        </template>
        <template x-if="notifications.length == 0">
            <div class="w-full text-center py-4 text-slate-800 font-semibold uppercase">
                Aucune notification pour le moment
            </div>
        </template>
        <!-- end a notification with two actions (accept/reject) -->
    </div>
</div>

<script src="<?= asset('js/notifications.js') ?>"></script>