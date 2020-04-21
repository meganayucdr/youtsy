import pandas as pd
import numpy as np
from pyanp import priority
import sys
import json

x = sys.argv[1]
data = json.loads(x)

mat4 = np.array(data)
result = priority.pri_eigen(mat4).tolist()
print(json.dumps(result))
