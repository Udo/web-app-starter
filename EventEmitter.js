class EventEmitter {
	constructor() {
		/** Map<eventName, Set<Function>> */
		this._topics = new Map();
	}

	on(topic, handler) {
		let set = this._topics.get(topic);
		if (!set) {
			set = new Set();
			this._topics.set(topic, set);
		}
		set.add(handler);
		return () => this.off(topic, handler);
	}

	off(topic, handler) {
		const set = this._topics.get(topic);
		if (!set) return;
		set.delete(handler);
		if (set.size === 0) this._topics.delete(topic);
	}

	emit(topic, ...args) {
		let count = 0;
		const set = this._topics.get(topic);
		if (!set) return count;
		for (const handler of [...set]) {
			const res = handler(...args);
			count++;
			if (res === 'remove_handler') {
				set.delete(handler);
			}
		}
		if (set.size === 0) this._topics.delete(topic);
		return count;
	}
}
