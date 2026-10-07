class Util
{
    static SleepPromise(ms)
    {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}
