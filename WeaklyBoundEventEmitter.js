/* An event emitter that doesn't hold strong references to its subscribers */

class WeaklyBoundEventEmitter {
	constructor() {
		/** Map<eventName, Set<WeakRef<Function>>> */
		this._topics = new Map();
		/** FinalizationRegistry to auto‑clean dead refs */
		this._cleanup = new FinalizationRegistry(({ topic, ref }) => {
			const set = this._topics.get(topic);
			if (set) set.delete(ref);
		});
	}

	on(topic, handler) {
		let set = this._topics.get(topic);
		if (!set) {
			set = new Set();
			this._topics.set(topic, set);
		}
		const ref = new WeakRef(handler);
		set.add(ref);
		// when `handler` is GC’d, remove its WeakRef from the set
		this._cleanup.register(handler, { topic, ref }, ref);
		return () => this.off(topic, handler); // unsubscribe token
	}

	off(topic, handler) {
		const set = this._topics.get(topic);
		if (!set) return;
		for (const ref of set) {
			if (ref.deref() === handler) {
				set.delete(ref);
				this._cleanup.unregister(ref);
			}
		}
	}

	emit(topic, ...args) {
		const set = this._topics.get(topic);
			if (!set) return;
			for (const ref of [...set]) {
				const fn = ref.deref();
				if (fn) {
					fn(...args);
				} else {
					// handler was GC’d—cleanup
					set.delete(ref);
			}
		}
	}
}
