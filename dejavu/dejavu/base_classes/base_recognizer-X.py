import abc
from time import time
from typing import Dict, List, Tuple

import numpy as np
from multiprocessing import Pool

from dejavu.config.settings import DEFAULT_FS

class BaseRecognizer(object, metaclass=abc.ABCMeta):
    global fingerprint_times
    fingerprint_times = []
    global hashes
    hashes = set()
    def __init__(self, dejavu):
        self.dejavu = dejavu
        self.Fs = DEFAULT_FS

    def recognize_func(self,channel):
        global hashes
        global fingerprint_times
        fingerprints, fingerprint_time = self.dejavu.generate_fingerprints(channel, Fs=self.Fs)
        fingerprint_times.append(fingerprint_time)
        hashes |= set(fingerprints)
        
    def _recognize(self, *data) -> Tuple[List[Dict[str, any]], int, int, int]:
        #fingerprint_times = []
        #hashes = set()  # to remove possible duplicated fingerprints we built a set.

        p = Pool(processes=8)
        #print(data)
        result_0 = p.map(self.recognize_func, data)
 #       for channel in data:
 #           fingerprints, fingerprint_time = self.dejavu.generate_fingerprints(channel, Fs=self.Fs)
 #           fingerprint_times.append(fingerprint_time)
 #           hashes |= set(fingerprints)

        matches, dedup_hashes, query_time = self.dejavu.find_matches(hashes)

        t = time()
        final_results = self.dejavu.align_matches(matches, dedup_hashes, len(hashes))
        align_time = time() - t

        return final_results, np.sum(fingerprint_times), query_time, align_time

    @abc.abstractmethod
    def recognize(self) -> Dict[str, any]:
        pass  # base class does nothing