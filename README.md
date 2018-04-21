## 相关笔记：[大话PHP设计模式：类自动载入、PSR-0规范、链式操作、11种面向对象设计模式实现和使用、OOP的基本原则和自动加载配置](https://segmentfault.com/a/1190000014219849)



# 一、类自动载入
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPL函数  (standard php librarys)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;类自动载入，尽管 `__autoload()` 函数也能自动加载类和接口，但更建议使用 `spl_autoload_register('函数名')` 函数。 `spl_autoload_register('函数名')` 提供了一种更加灵活的方式来实现类的自动加载（同一个应用中，可以支持任意数量的加载器，比如第三方库中的）。因此，不再建议使用 `__autoload()` 函数，在以后的版本中它可能被弃用。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`spl_autoload_register('函数名')`自动加载类，可以重复使用，不会报函数名相同的错误！可以实现我们自定义函数的激活，它的返回值是bool类型：true or false。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果不写参数，那么它会去调用 spl_autoload()方法，这个方法默认会执行下面的语句：**require_once 类名.php  或  类名.inc**。

    spl_autoload_register('函数名')
    
    function 函数名($class){<br>
     require __DIR__.'/'.$class.'.php';<br>
    }
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们可以在入口文件中，使用spl_autoload_register()来完成类的自动加载
# 二、PSR-0规范
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. **命名空间必须与绝对路径一致**（文件里写命名空间从根目录下它所在文件夹开始到它的上一层文件夹名）
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. **类名首字母必须大写**
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. **除入口文件外，其它的".php"文件中只能存在一个类，不能有外部可执行的代码**



# 三、链式操作

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;链式操作最重要的是**return $this**
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;要求每个方法体内必须`return $this`
```
class Database
{
    static private $db;

    private function __construct()
    {

    }

    static function getInstance()
    {
        if (empty(self::$db)) {
            self::$db = new self;
            return self::$db;
        } else {
            return self::$db;
        }
    }

    function where($where)
    {
        return $this;
    }

    function order($order)
    {
        return $this;
    }

    function limit($limit)
    {
        return $this;
    }

    function query($sql)
    {
        echo "SQL: $sql\n";
    }
}
```
链式操作能简化代码，比如：
    $db=new DataBase();
    $db->where("id>10")->order(2)->limit(10);


# 四、魔术常量和魔术方法
## 4.1 魔术常量
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; `__FILE__` 文件的完整路径和文件名，如果用在被包含的文件中，则返回被包含文件路径名。
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; `__DIR__`  文件的所在目录，不包括文件名。 等价于dirname(__FILE__) 除了根目录，不包括末尾的反斜杠
，basename(__FILE__)返回的是文件名
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`__FUNCTION__` 返回的是函数名称
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`__METHOD__` 返回的是类的方法名  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`__CLASS__` 返回的是类的名称
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`__NAMESPACE__` 返回的是当前命名空间的名称 
## 4.2 PHP魔术方法的使用
    __get/ __set   # 访问不存在的属性
    __call/ __callStatic   # 调用不存在的方法
    __toString   # 对象作为字符串使用
    __invoke    # 对象作为方法使用

# 五、三种基本设计模式
## 5.1 工厂模式
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们定义一个专门用来创建其它对象的类。 这样在需要调用某个类的时候，我们就**不需要去使用`new`关键字实例化这个类**，而是通过我们的工厂类调用某个方法得到类的实例。
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;好处：当我们对象所对应的类的类名发生变化的时候，只需要在工厂类里面修改即可，而不用一个一个的去修改

```
class A
{
    //不允许类直接实例化 或克隆
    private function __construct(){}
    private function __clone(){}
}

class B
{
    //不允许类直接实例化 或克隆
    private function __construct(){}
    private function __clone(){}
}


class Factory
{
    public static function getInstance($class)
    {
        //类对象的获取方式通过工厂类 产生
        return new $class();
    }
}

//使用
$a = Factory::getInstance('A');
$b = Factory::getInstance('B');
```


## 5.2 单例模式
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单例模式的最大好处就是减少资源的浪费，保证整个环境中只存在一个实例化的对象，特别适合资源连接类的编写。
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**只实例化一次，内部实例化，对外只有一个开放方法**。

    // 单例模式（口诀：三私一公）
    class Singleton{
    	//私有化构造方法，禁止外部实例化对象
    	private function __construct(){}
    	//私有化__clone，防止对象被克隆
    	private function __clone(){}
    	//私有化内部实例化的对象
    	private static $instance = null;
    	// 公有静态实例方法
    	public static function getInstance(){
    		if(self::$instance == null){
    			//内部实例化对象
    			self::$instance = new self();    
    		}
    		return self::$instance;
    	}
    }
## 5.3 注册树模式
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单例模式解决的是如何在整个项目中创建唯一对象实例的问题，工厂模式解决的是如何不通过new建立实例对象的方法。 那么注册树模式想解决什么问题呢？ 在考虑这个问题前，我们还是有必要考虑下前两种模式目前面临的局限。  首先，单例模式创建唯一对象的过程本身还有一种判断，即判断对象是否存在。存在则返回对象，不存在则创建对象并返回。 每次创建实例对象都要存在这么一层判断。 工厂模式更多考虑的是扩展维护的问题。 总的来说，单例模式和工厂模式可以产生更加合理的对象。怎么方便调用这些对象呢？而且在项目内建立的对象好像散兵游勇一样，不便统筹管理安排啊。因而，注册树模式应运而生。不管你是通过单例模式还是工厂模式还是二者结合生成的对象，都统统给我“插到”注册树上。我用某个对象的时候，直接从注册树上取一下就好。这和我们使用全局变量一样的方便实用。 而且注册树模式还为其他模式提供了一种非常好的想法
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注册器解决对象多次调用场景， 减少new新的对象的次数，防止重复创建对象。看对象是否是同一个对象方法，`var_dump`后看`id`，`#1`，`#2`等字样`id`，它是`php`内部对象唯一标识

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注册器模式：`Register.php`,用来将一些对象注册到全局的注册树上，可以在任何地方访问。`set()`:将对象映射到全局树上，`_unset()`:从树上移除，`get()`:去注册到树上的对象。



```
class Register
{
    protected static $objects;
    //注册
    static function set($alias, $object)
    {
        self::$objects[$alias] = $object;
    }
    //获取
    static function get($key)
    {
        if (!isset(self::$objects[$key]))
        {
            return false;
        }
        return self::$objects[$key];
    }
    //注销
    function _unset($alias)
    {
        unset(self::$objects[$alias]);
    }
}
```

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![clipboard.png](/img/bV7Kin)

## 5.4 三种基本模式总结
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**1、工厂模式的特征有一个统一生成对象的入口，使用工厂方式生成对象，而不是在代码直接`new`。为了后期更好的扩展和修改**

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**2、单例模式的特征是对象不可外部实例并且只能实例化一次，当对象已存在就直接返回该对象**

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**3、注册树模式的特征是对象不用在通过类创建，具有全局对象树类。解决对象多次调用场景， 减少`new`新的对象的次数**


# 六、适配器模式

1. 可以将截然不同的函数接口封装成统一的API

2. 实际应用举例：`PHP`的数据库操作有`mysql/mysqli/pdo`三种，可以用适配器模式统一成一致。类似的场景还有`cache`适配器，可以将`memcache/redis/file/apc`等不同的缓存函数统一成一致的接口。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用适配器策略是为了更好的兼容。类似于手机电源适配器，如果能用一个充电器对所有手机充电当然是最方便的。无论什么手机，都只需要拿一个充电器。否则，不同手机不同充电器，太麻烦。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新建一个接口 `IDatabase`  然后在这个接口里面申明统一的方法体，再让不同的类去实现这个接口，和重写其抽象方法。当我们在入口文件使用到不同的类的时候，就只是实例化的类名不同，其它调用方法体的地方都一致。 

```
/**
* 1、新建一个接口 IDatabase  然后在这个接口里面申明统一的方法体
*/
interface IDatabase
{
    //连接
    function connect($host, $user, $passwd, $dbname);
    //查询
    function query($sql);
    //关闭连接
    function close();
}

```

```
/**
* 2、让不同的类去实现这个接口，和重写其抽象方法。
*/
class MySQLi implements IDatabase
{
    protected $conn;

    function connect($host, $user, $passwd, $dbname)
    {
        $conn = mysqli_connect($host, $user, $passwd, $dbname);
        $this->conn = $conn;
    }

    function query($sql)
    {
        return mysqli_query($this->conn, $sql);
    }

    function close()
    {
        mysqli_close($this->conn);
    }
}


```


# 七、策略模式
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**策略模式：**
1. 策略模式，将一组特定的行为和算法封装成类，以适应某些特定的上下文环境，这种模式就是策略模式
2. 实际应用举例，假如一个电商网站系统，针对男性女性用户要各自跳转到不同的商品类名，并且所有广告位展示不同的广告，传统的做法是加入`if...else...` 判断。如果新增加一种用户类型，只需要新增加一种策略即可
3. 使用策略模式可以实现Ioc ,**`依赖倒置，控制反转`**，面向对象很重要的一个思想是解耦

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**策略模式实现：**
1. 定义一个策略接口文件，定义策略接口，声明策略
2. 定义具体类，实现策略接口，重写策略方法

```
// 策略的接口文件：约定策略的所有行为
interface UserStrategy {
    function showAd();
    function showCategory();
} 

// 实现接口的所有方法
class FemaleUserStrategy implements UserStrategy {
    function showAd()
    {
        echo "2018新款女装";
    }
    function showCategory()
    {
        echo "女装";
    }
} 

// 实现接口的所有方法
class MaleUserStrategy implements UserStrategy  {

    function showAd()
    {
        echo "iPhone X";
    }

    function showCategory()
    {
        echo "电子产品";
    }
} 
```
```

class Page
{
    /**
     * @var \IMooc\UserStrategy
     */
    protected $strategy;
    function index()
    {
        echo "AD:";
        $this->strategy->showAd();
        echo "<br/>";

        echo "Category:";
        $this->strategy->showCategory();
        echo "<br/>";
    }

    function setStrategy(\IMooc\UserStrategy $strategy)
    {
        $this->strategy = $strategy;
    }
}

$page = new Page;
if (isset($_GET['female'])) {
    $strategy = new \IMooc\FemaleUserStrategy();
} else {
    $strategy = new \IMooc\MaleUserStrategy();
}
$page->setStrategy($strategy);
$page->index();
```

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;不需要在`page`类中判断业务逻辑，如果在`index`里面写逻辑判断 `if男else女` 就会存在‘**依赖**’，这个是不好的，存在很大耦合，所以把逻辑写在外部，并且在`page`里面增加一个`set`的方法，这个方法的作用就是‘**注入**’一个对象。只有再使用时才绑定，这样以后更方便的替换修改`MaleUserStratey`类，实现了两个类的解耦，这就是策略模式的依赖倒置，**实现了硬编码到解耦**。

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**依赖倒置原则：**
A. 高层次的模块不应该依赖于低层次的模块，他们都应该依赖于抽象
B. 抽象不应该依赖于具体实现，具体实现应该依赖于抽象

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在这里不管是Page，还是低层次的MaleUserStratey和FemaleUserStrategy都依赖于抽象userStrategy这个抽象，而UserStrategy不依赖于具体实现，具体实现Female和male都依赖于UserStrategy这个抽象。有点绕，应该是这个关系。






# 八、数据对象映射模式
&nbsp;&nbsp;&nbsp;&nbsp;数据对象映射模式，是将对象和数据存储映射起来，对一个对象的操作会映射为对数据存储的操作，比我们在代码中new一个对象，那么使用该模式就可以将对对象的一些操作，比如说我们设置的一些属性，它就会自动保存到数据库，跟数据库中表的一条记录对应起来，数据对象映射模式就是将sql的操作转化为对象的操作。

&nbsp;&nbsp;&nbsp;&nbsp;对象关系映射（英语：Object Relation Mapping，简称ORM，或O/RM，或O/R mapping），是一种程序技术，用于实现面向对象编程语言里不同类型系统的数据之间的转换。从效果上说，它其实是创建了一个可在编程语言里使用的--“虚拟对象数据库”。
&nbsp;&nbsp;&nbsp;&nbsp;面向对象是从软件工程基本原则（如耦合、聚合、封装）的基础上发展起来的，而关系数据库则是从数学理论发展而来的，两套理论存在显著的区别。为了解决这个不匹配的现象，对象关系映射技术应运而生。简单的说：`ORM`相当于中继数据

&nbsp;&nbsp;&nbsp;&nbsp;实例，在代码中实现数据对象映射模式，我们将写一个`ORM`类，将复杂的`SQL`语句映射成对象属性的操作。结合使用数据对象映射模式，工厂模式，注册模式混合使用


```
class User
{
    protected $id;
    protected $data;
    protected $db;
    protected $change = false;

    function __construct($id)
    {
        $this->db = Factory::getDatabase();
        $res = $this->db->query("select * from user where id = $id limit 1");
        $this->data = $res->fetch_assoc();
        $this->id = $id;
    }

    function __get($key)
    {
        if (isset($this->data[$key]))
        {
            return $this->data[$key];
        }
    }

    function __set($key, $value)
    {
        $this->data[$key] = $value;
        $this->change = true;
    }

    /**
     * 析构方法
     */
    function __destruct()
    {
        if ($this->change)
        {
            foreach ($this->data as $k => $v)
            {
                $fields[] = "$k = '{$v}'";
            }
            $this->db->query("update user set " . implode(', ', $fields) . "where
            id = {$this->id} limit 1");
        }
    }
}

```

```
class Factory
{
    /** 注册$user
     * @param $id
     * @return User
     */
    static function getUser($id)
    {
        $key = 'user_'.$id;
        $user = Register::get($key);
        if (!$user) {
            $user = new User($id);
            Register::set($key, $user);
        }
        return $user;
    }
}
```


# 九、观察者模式
	1. 观察者模式（ `Observer` ），当一个对象状态发生改变时，依赖它的对象全部会收到通知，并自动更新
	2. 场景：一个事件发生后，要执行一连串更新操作。传统的编程方式，就是在事件的代码之后直接加入处理逻辑。当更新的逻辑增多后，代码会变得难以维护。这种方式是耦合的，入侵式的，增加新的逻辑需要修改事件主体的代码
	3. 观察者模式实现了低耦合，非入侵式的通知与更新机制

&nbsp;&nbsp;&nbsp;&nbsp;**观察者模式实现：**
1. 所有观察者对象实现统一接口
2. 被观察对象持有观察者句柄,使用`Add`观察者()方法
3. 某一场合，调用观察方法。`Foreach`(观察者句柄数组 `as` 某一个观察者)


```
//事件基类
abstract class EventGenerator {
    private $observers = array();

    function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    function notify()
    {
        foreach($this->observers as $observer)
        {
            $observer->update();
        }
    }

} 
```

```
//观察者接口
interface Observer
{
    function update($event_info = null);
}
```

```
class Event extends EventGenerator
{
    /**
     * 触发事件
     */
    function trigger()
    {
        echo "Event<br/>\n";
        $this->notify();
    }
}

class Observer1 implements Observer
{
    function update($event_info = null)
    {
        echo "逻辑1<br />\n";
    }
}

class Observer2 implements Observer
{
    function update($event_info = null)
    {
        echo "逻辑2<br />\n";
    }
}

$event = new Event;
$event->addObserver(new Observer1);
$event->addObserver(new Observer2);
$event->trigger();
```


# 十、原型模式
1. 原型模式与工程模式作用类似，都是用来创建对象
2. 与工厂模式的实现不同，原型模式是 先创建好一个原型对象，然后通过`clone`原型对象来创建新的对象。这样就**免去了类创建时的重复初始化操作**
3. 原型模式适用于大对象的创建，创建一个大对象需要很大的开销，如果每次都`new`就会消耗很大，原型模式仅需**内存拷贝**即可

&nbsp;&nbsp;&nbsp;&nbsp;![clipboard.png](/img/bV7KCv)

# 十一、装饰器模式
1. 装饰器模式（`Decorator`），可以**动态地添加修改类的功能**
2. 一个类提供了一项功能，如果要在修改并添加额外的功能，传统的编程模式，需要写一个子类继承它，并重新实现类的方法
3. 使用装饰器模式，仅需在运行时添加一个装饰器对象即可实现，可以实现最大的灵活性

```
class Canvas
{
    public $data;
    protected $decorators = array();
    //添加装饰器
    function addDecorator(DrawDecorator $decorator)
    {
        $this->decorators[] = $decorator;
    }
    //执行装饰器前置操作 先进先出原则
    function beforeDraw()
    {
        foreach($this->decorators as $decorator)
        {
            $decorator->beforeDraw();
        }
    }
    //执行装饰器后置操作 先进后出原则
    function afterDraw()
    {
        //注意，反转
        $decorators = array_reverse($this->decorators);
        foreach($decorators as $decorator)
        {
            $decorator->afterDraw();
        }
    }

    function draw()
    {
        //调用装饰器前置操作
        $this->beforeDraw();
        foreach($this->data as $line)
        {
            foreach($line as $char)
            {
                echo $char;
            }
            echo "<br />\n";
        }
        //调用装饰器后置操作
        $this->afterDraw();
    }
```

```
//装饰器接口
interface DrawDecorator
{
    function beforeDraw();
    function afterDraw();
}
```

```
//实现颜色装饰器实现接口
class ColorDrawDecorator implements DrawDecorator
{
    protected $color;
    function __construct($color = 'red')
    {
        $this->color = $color;
    }
    function beforeDraw()
    {
        echo "<div style='color: {$this->color};'>";
    }
    function afterDraw()
    {
        echo "</div>";
    }
}
```

&nbsp;&nbsp;&nbsp;&nbsp;![clipboard.png](/img/bV7KL9)


# 十二、迭代器模式
1. 迭代器模式，在不需要了解内部实现的前提下，遍历一个聚合对象的内部元素
2. 相比传统的编程模式，迭代器模式可以隐藏遍历元素所需的操作

&nbsp;&nbsp;&nbsp;&nbsp;应用场景：遍历数据库表，拿到所有的user对象，然后用佛 `foreach` 循环，在循环的过程中修改某些字段的

```
class AllUser implements \Iterator
{
    protected $index = 0;
    protected $data = [];

    public function __construct()
    {
        $link = mysqli_connect('192.168.0.91', 'root', '123', 'xxx');
        $rec = mysqli_query($link, 'select id from doc_admin');
        $this->data = mysqli_fetch_all($rec, MYSQLI_ASSOC);
    }

    //1 重置迭代器
    public function rewind()
    {
        $this->index = 0;
    }

    //2 验证迭代器是否有数据
    public function valid()
    {
        return $this->index < count($this->data);
    }

    //3 获取当前内容
    public function current()
    {
        $id = $this->data[$this->index];
        return User::find($id);
    }

    //4 移动key到下一个
    public function next()
    {
        return $this->index++;
    }


    //5 迭代器位置key
    public function key()
    {
        return $this->index;
    }
}

//实现迭代遍历用户表
$users = new AllUser();
//可实时修改
foreach ($users as $user){
    $user->add_time = time();
    $user->save();
}
```
# 十三、代理模式
1. 在客户端与实体之间建立一个代理对象（`proxy`），客户端对实体进行的操作全部委派给代理对象，隐藏实体的具体实现细节。
2. `Proxy`还可以与业务代码分离，部署到另外的服务器，业务代码中通过`RPC`来委派任务。
     
&nbsp;&nbsp;&nbsp;&nbsp;代理模式：数据库主从，通过代理设置主从读写设置

&nbsp;&nbsp;&nbsp;&nbsp;**传统方式：**

&nbsp;&nbsp;&nbsp;&nbsp;![clipboard.png](/img/bV7MYr)

&nbsp;&nbsp;&nbsp;&nbsp;需要手动的去选择主库和从库。

&nbsp;&nbsp;&nbsp;&nbsp;**代理模式：**
```
//做约束接口
interface IUserProxy
{
    function getUserName($id);
    function setUserName($id, $name);
}
```

```
class Proxy implements IUserProxy
{
    function getUserName($id)
    {
        $db = Factory::getDatabase('slave');
        $db->query("select name from user where id =$id limit 1");
    }
    function setUserName($id, $name)
    {
        $db = Factory::getDatabase('master');
        $db->query("update user set name = $name where id =$id limit 1");
    }
}
```

```
$id = 1;
$proxy = new \IMooc\Proxy();
$proxy->getUser($id);
$proxy->setUser($id, array('name' => 'wang'));
```

# 十四、面向对象编程的基本原则
1. **单一职责：**`一个类，只需做好一件事情`。不要使用一个类来完成很复杂的功能，而是拆分设计成更小更具体的类。
2. **开放封闭原则：**`一个类，应该可以扩展，而不可修改`。一个类在实现之后，应该是对扩展开放，对修是改封闭的，不应该使用修改来增加功能，而是通过扩展来增加功能。
3. **依赖倒置：**一个类，不应该强制依赖另一个类。每个类对另外一个类都是可以替换的。如：有A、B两个类，A需要依赖B类，不应该在A类中直接调用B类，而是要使用**`依赖注入`**的方式，通过使用注入，将A类依赖的B类的对象注入给A类，B类对于A类来说就是可以替换的。如果C类实现了和B类一样的接口，那对于A类，B和C也是可以随意替换的。
4. **配置化：** `尽可能的使用配置，而不是使用硬编码`。数据参数和常量应该放在配置文件中。像类的关系的定义，也应该是可以配置的。
5. **面向接口编程**，而不是面向实现编程：只需要关心接口，不需要关心实现。所有的代码，它只需要关心某一个类实现了哪些接口，而不需要关心这个类的具体实现。

# 十五、自动加载配置

&nbsp;&nbsp;&nbsp;&nbsp;如果实现`ArrayAcess`接口，则能使一个对象属性的访问，可以以数组的方式进行

```
class Config implements \ArrayAccess
{
    protected $path;
    protected $configs = array();

    //配置文件目录
    function __construct($path)
    {
        $this->path = $path;
    }

    //获取数组的key
    function offsetGet($key)
    {
        if (empty($this->configs[$key]))
        {
            $file_path = $this->path.'/'.$key.'.php';
            $config = require $file_path;
            $this->configs[$key] = $config;
        }
        return $this->configs[$key];
    }

    //设置数组的key
    function offsetSet($key, $value)
    {
        throw new \Exception("cannot write config file.");
    }

    function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }
}
```
&nbsp;&nbsp;&nbsp;&nbsp;**controller.php**
```
$config = array(
    'home' => array(
        'decorator' => array(
            //'App\Decorator\Login',
            //'App\Decorator\Template',
            //'App\Decorator\Json',
        ),
    ),
    'default' => 'hello world',
);
return $config;
```

&nbsp;&nbsp;&nbsp;&nbsp;![clipboard.png](/img/bV7OBP)

**完！**

[**参考教程：**韩天峰-大话PHP设计模式](https://www.imooc.com/learn/236)
